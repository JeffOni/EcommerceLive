<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class VerifyAdminEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:verify-email {email : El email del usuario admin a verificar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar manualmente el email de un usuario administrativo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        // Validar el formato del email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            $this->error('El formato del email no es válido.');
            return 1;
        }

        // Buscar el usuario
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No se encontró ningún usuario con el email: {$email}");
            return 1;
        }

        // Verificar que sea un usuario administrativo
        if (!$user->hasAnyRole(['admin', 'super_admin'])) {
            $this->error("El usuario {$email} no tiene rol administrativo.");
            $this->info("Roles actuales: " . $user->roles->pluck('name')->implode(', '));
            return 1;
        }

        // Verificar si ya está verificado
        if ($user->hasVerifiedEmail()) {
            $this->warn("El usuario {$email} ya tiene el email verificado.");
            $this->info("Verificado el: " . $user->email_verified_at->format('Y-m-d H:i:s'));
            return 0;
        }

        // Verificar el email
        $user->markEmailAsVerified();

        $this->info("✅ Email verificado exitosamente para el usuario:");
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Nombre', $user->name . ' ' . $user->last_name],
                ['Email', $user->email],
                ['Roles', $user->roles->pluck('name')->implode(', ')],
                ['Verificado el', $user->fresh()->email_verified_at]
            ]
        );

        return 0;
    }
}
