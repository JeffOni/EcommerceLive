<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal del admin
     */
    public function index()
    {
        // Estadísticas generales
        $stats = $this->getGeneralStats();

        // Gráficos de ventas
        $salesChart = $this->getSalesChart();

        // Órdenes recientes
        $recentOrders = $this->getRecentOrders();

        // Pagos pendientes de verificación
        $pendingPayments = $this->getPendingPayments();

        // Alertas y notificaciones
        $alerts = $this->getAlerts();

        return view('admin.dashboard', compact(
            'stats',
            'salesChart',
            'recentOrders',
            'pendingPayments',
            'alerts'
        ));
    }

    /**
     * Obtener estadísticas generales
     */
    private function getGeneralStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        return [
            'total_orders' => Order::count(),
            'orders_today' => Order::whereDate('created_at', $today)->count(),
            'orders_this_month' => Order::where('created_at', '>=', $thisMonth)->count(),
            'orders_growth' => $this->calculateGrowth(
                Order::whereBetween('created_at', [$lastMonth, $thisMonth])->count(),
                Order::where('created_at', '>=', $thisMonth)->count()
            ),

            'total_revenue' => Order::where('status', '>=', 2)->sum('total'),
            'revenue_today' => Order::where('status', '>=', 2)->whereDate('created_at', $today)->sum('total'),
            'revenue_this_month' => Order::where('status', '>=', 2)->where('created_at', '>=', $thisMonth)->sum('total'),
            'revenue_growth' => $this->calculateGrowth(
                Order::where('status', '>=', 2)->whereBetween('created_at', [$lastMonth, $thisMonth])->sum('total'),
                Order::where('status', '>=', 2)->where('created_at', '>=', $thisMonth)->sum('total')
            ),

            'pending_payments' => Payment::where('status', 'pending')->whereNotNull('receipt_path')->count(),
            'verified_payments_today' => Payment::whereIn('status', ['approved', 'rejected'])->whereDate('verified_at', $today)->count(),

            'total_customers' => User::whereHas('orders')->count(),
            'new_customers_today' => User::whereDate('created_at', $today)->count(),
        ];
    }

    /**
     * Obtener datos para el gráfico de ventas
     */
    private function getSalesChart()
    {
        $last30Days = collect();

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $orders = Order::where('status', '>=', 2)
                ->whereDate('created_at', $date)
                ->count();

            $revenue = Order::where('status', '>=', 2)
                ->whereDate('created_at', $date)
                ->sum('total');

            $last30Days->push([
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d/m'),
                'orders' => $orders,
                'revenue' => (float) $revenue
            ]);
        }

        return $last30Days;
    }

    /**
     * Obtener órdenes recientes
     */
    private function getRecentOrders()
    {
        return Order::with(['user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Obtener pagos pendientes de verificación
     */
    private function getPendingPayments()
    {
        return Payment::with('user')
            ->where('status', 'pending')
            ->whereNotNull('receipt_path')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Obtener alertas importantes
     */
    private function getAlerts()
    {
        $alerts = [];

        // Pagos pendientes hace más de 24 horas
        $oldPendingPayments = Payment::where('status', 'pending')
            ->whereNotNull('receipt_path')
            ->where('created_at', '<', Carbon::now()->subDay())
            ->count();

        if ($oldPendingPayments > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-clock',
                'title' => 'Pagos pendientes',
                'message' => "Hay {$oldPendingPayments} pagos pendientes de verificación hace más de 24 horas",
                'action_url' => route('admin.payments.verification'),
                'action_text' => 'Revisar pagos'
            ];
        }

        // Órdenes en procesamiento hace más de 3 días
        $oldProcessingOrders = Order::where('status', 2)
            ->where('updated_at', '<', Carbon::now()->subDays(3))
            ->count();

        if ($oldProcessingOrders > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'fas fa-box',
                'title' => 'Órdenes en procesamiento',
                'message' => "Hay {$oldProcessingOrders} órdenes en procesamiento hace más de 3 días",
                'action_url' => route('admin.orders.index'),
                'action_text' => 'Ver órdenes'
            ];
        }

        // Productos con stock bajo (opcional - si tienes gestión de inventario)
        // $lowStockProducts = Product::where('stock', '<', 10)->count();

        return $alerts;
    }

    /**
     * Calcular crecimiento porcentual
     */
    private function calculateGrowth($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * API para actualizar estadísticas en tiempo real
     */
    public function getStats()
    {
        return response()->json($this->getGeneralStats());
    }
}
