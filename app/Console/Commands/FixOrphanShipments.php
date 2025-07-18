<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Shipment;

class FixOrphanShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:fix-orphan-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige órdenes con envíos sin repartidor asignado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando órdenes con envíos sin repartidor asignado...');

        // Buscar envíos sin repartidor asignado
        $orphanShipments = Shipment::whereNull('delivery_driver_id')
            ->with('order')
            ->get();

        if ($orphanShipments->count() === 0) {
            $this->info('No se encontraron envíos sin repartidor asignado.');
            return;
        }

        $this->info("Se encontraron {$orphanShipments->count()} envíos sin repartidor asignado.");

        $choice = $this->choice(
            '¿Qué acción deseas realizar?',
            [
                'delete' => 'Eliminar los envíos y regresar órdenes a estado "Preparando"',
                'list' => 'Solo mostrar la lista de órdenes afectadas',
                'cancel' => 'Cancelar operación'
            ],
            'list'
        );

        switch ($choice) {
            case 'delete':
                $this->deleteOrphanShipments($orphanShipments);
                break;
            case 'list':
                $this->listOrphanShipments($orphanShipments);
                break;
            case 'cancel':
                $this->info('Operación cancelada.');
                break;
        }
    }

    private function deleteOrphanShipments($orphanShipments)
    {
        $this->info('Eliminando envíos sin repartidor y actualizando órdenes...');

        $bar = $this->output->createProgressBar($orphanShipments->count());
        $bar->start();

        foreach ($orphanShipments as $shipment) {
            // Actualizar orden a estado "Preparando" (3)
            if ($shipment->order) {
                $shipment->order->update(['status' => 3]);
            }

            // Eliminar envío huérfano
            $shipment->delete();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Operación completada. Los envíos sin repartidor fueron eliminados y las órdenes regresaron a estado "Preparando".');
    }

    private function listOrphanShipments($orphanShipments)
    {
        $this->info('Órdenes con envíos sin repartidor asignado:');
        $this->newLine();

        $headers = ['Orden ID', 'Cliente', 'Total', 'Estado Orden', 'Estado Envío', 'Fecha Creación'];
        $rows = [];

        foreach ($orphanShipments as $shipment) {
            $order = $shipment->order;

            $orderStatus = match ($order->status) {
                1 => 'Pendiente',
                2 => 'Pago Verificado',
                3 => 'Preparando',
                4 => 'Asignado',
                5 => 'En Camino',
                6 => 'Entregado',
                7 => 'Cancelado',
                default => 'Desconocido'
            };

            $shipmentStatus = match ($shipment->status) {
                1 => 'Pendiente',
                2 => 'Asignado',
                3 => 'En Camino',
                4 => 'Entregado',
                5 => 'Cancelado',
                default => 'Desconocido'
            };

            $rows[] = [
                $order->id,
                $order->user->name ?? 'N/A',
                '$' . number_format($order->total, 2),
                $orderStatus,
                $shipmentStatus,
                $shipment->created_at->format('d/m/Y H:i')
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();
        $this->info('Para corregir estas órdenes, ejecuta el comando nuevamente y selecciona "delete".');
    }
}
