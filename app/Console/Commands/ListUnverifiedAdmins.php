<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUnverifiedAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:list-unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar usuarios administrativos con emails sin verificar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Buscando usuarios administrativos sin verificación de email...');

        // Obtener usuarios admin sin email verificado
        $unverifiedAdmins = User::role(['admin', 'super_admin'])
            ->whereNull('email_verified_at')
            ->with('roles')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($unverifiedAdmins->isEmpty()) {
            $this->info('✅ Todos los usuarios administrativos tienen sus emails verificados.');
            return 0;
        }

        $this->warn("⚠️  Se encontraron {$unverifiedAdmins->count()} usuarios administrativos sin verificación:");

        $tableData = [];
        foreach ($unverifiedAdmins as $user) {
            $tableData[] = [
                $user->id,
                $user->name . ' ' . $user->last_name,
                $user->email,
                $user->roles->pluck('name')->implode(', '),
                $user->created_at->format('Y-m-d H:i:s'),
                $user->created_at->diffForHumans()
            ];
        }

        $this->table(
            ['ID', 'Nombre', 'Email', 'Roles', 'Creado', 'Hace'],
            $tableData
        );

        $this->newLine();
        $this->info('💡 Para verificar manualmente un email, usa:');
        $this->line('   php artisan admin:verify-email <email>');

        $this->newLine();
        $this->info('💡 Para verificar todos los emails admin de una vez:');
        $this->line('   php artisan admin:verify-all-emails');

        return 0;
    }
}
