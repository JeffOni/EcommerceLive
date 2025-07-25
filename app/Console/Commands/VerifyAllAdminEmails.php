<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyAllAdminEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:verify-all-emails {--force : Forzar verificación sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar todos los emails de usuarios administrativos sin verificar';

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
            ->get();

        if ($unverifiedAdmins->isEmpty()) {
            $this->info('✅ Todos los usuarios administrativos ya tienen sus emails verificados.');
            return 0;
        }

        $this->warn("⚠️  Se encontraron {$unverifiedAdmins->count()} usuarios administrativos sin verificación:");

        // Mostrar lista de usuarios
        $tableData = [];
        foreach ($unverifiedAdmins as $user) {
            $tableData[] = [
                $user->name . ' ' . $user->last_name,
                $user->email,
                $user->roles->pluck('name')->implode(', ')
            ];
        }

        $this->table(['Nombre', 'Email', 'Roles'], $tableData);

        // Pedir confirmación si no se usa --force
        if (!$this->option('force')) {
            if (!$this->confirm('¿Deseas verificar todos estos emails administrativos?')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        // Verificar todos los emails
        $verified = 0;
        $this->info('🔄 Verificando emails...');

        foreach ($unverifiedAdmins as $user) {
            $user->markEmailAsVerified();
            $verified++;
            $this->line("✅ {$user->email} - verificado");
        }

        $this->newLine();
        $this->info("🎉 Se verificaron exitosamente {$verified} emails administrativos.");

        return 0;
    }
}
