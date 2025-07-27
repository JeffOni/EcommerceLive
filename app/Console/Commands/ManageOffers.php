<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ManageOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:manage {action=status : Acción a realizar (status|clean|create)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gestiona las ofertas de productos: status, clean o create';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'status':
                $this->showOffersStatus();
                break;
            case 'clean':
                $this->cleanExpiredOffers();
                break;
            case 'create':
                $this->createSampleOffers();
                break;
            default:
                $this->error('Acción no válida. Usa: status, clean o create');
        }
    }

    private function showOffersStatus()
    {
        $activeOffers = Product::where('is_on_offer', true)->get();
        $validOffers = Product::onValidOffer()->get();
        $expiredOffers = Product::where('is_on_offer', true)
            ->where('offer_ends_at', '<', now())
            ->get();

        $this->info('📊 Estado de las Ofertas');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line("🎯 Total de productos con oferta: {$activeOffers->count()}");
        $this->line("✅ Ofertas válidas (activas): {$validOffers->count()}");
        $this->line("❌ Ofertas vencidas: {$expiredOffers->count()}");

        if ($validOffers->count() > 0) {
            $this->line("\n🔥 Ofertas Activas:");
            foreach ($validOffers as $product) {
                $this->line("   • {$product->name} - {$product->discount_percentage}% OFF");
                $this->line("     💰 \${$product->price} → \${$product->offer_price}");
                $this->line("     📅 Hasta: {$product->offer_ends_at->format('d/m/Y H:i')}");
            }
        }

        if ($expiredOffers->count() > 0) {
            $this->line("\n⏰ Ofertas Vencidas (necesitan limpieza):");
            foreach ($expiredOffers as $product) {
                $this->line("   • {$product->name} - Venció: {$product->offer_ends_at->format('d/m/Y H:i')}");
            }
        }
    }

    private function cleanExpiredOffers()
    {
        $expiredOffers = Product::where('is_on_offer', true)
            ->where('offer_ends_at', '<', now())
            ->get();

        if ($expiredOffers->isEmpty()) {
            $this->info('✅ No hay ofertas vencidas para limpiar.');
            return;
        }

        $this->warn("🧹 Limpiando {$expiredOffers->count()} ofertas vencidas...");

        foreach ($expiredOffers as $product) {
            $product->update([
                'is_on_offer' => false,
                'offer_price' => null,
                'offer_percentage' => null,
                'offer_starts_at' => null,
                'offer_ends_at' => null,
                'offer_name' => null
            ]);

            $this->line("   ✓ {$product->name} - Oferta removida");
        }

        $this->info("🎉 ¡{$expiredOffers->count()} ofertas vencidas han sido limpiadas!");
    }

    private function createSampleOffers()
    {
        $products = Product::where('is_on_offer', false)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {
            $this->warn('⚠️  No hay productos disponibles para crear ofertas.');
            return;
        }

        $offers = [
            ['name' => 'Flash Sale', 'percentage' => 25, 'days' => 3],
            ['name' => 'Weekend Special', 'percentage' => 20, 'days' => 2],
            ['name' => 'Clearance', 'percentage' => 35, 'days' => 7],
            ['name' => 'Daily Deal', 'percentage' => 15, 'days' => 1],
            ['name' => 'Limited Time', 'percentage' => 30, 'days' => 5],
        ];

        $this->info('🎯 Creando ofertas de muestra...');

        foreach ($products as $index => $product) {
            $offer = $offers[$index];
            $discountAmount = $product->price * ($offer['percentage'] / 100);
            $offerPrice = $product->price - $discountAmount;

            $product->update([
                'is_on_offer' => true,
                'offer_price' => $offerPrice,
                'offer_percentage' => $offer['percentage'],
                'offer_starts_at' => Carbon::now(),
                'offer_ends_at' => Carbon::now()->addDays($offer['days']),
                'offer_name' => $offer['name']
            ]);

            $this->line("   ✓ {$product->name} - {$offer['percentage']}% OFF ({$offer['name']})");
            $this->line("     💰 \${$product->price} → \$" . number_format($offerPrice, 2));
        }

        $this->info("🎉 ¡{$products->count()} ofertas creadas exitosamente!");
    }
}
