<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing email configuration...');

        try {
            // Mostrar configuración actual
            $this->info('MAIL Configuration:');
            $this->info('Host: ' . config('mail.mailers.smtp.host'));
            $this->info('Port: ' . config('mail.mailers.smtp.port'));
            $this->info('Username: ' . config('mail.mailers.smtp.username'));
            $this->info('Encryption: ' . config('mail.mailers.smtp.encryption'));
            $this->info('From Address: ' . config('mail.from.address'));

            // Intentar enviar email
            Mail::to('servicioalcliente@lagofish.store')->send(
                new ContactMail(
                    'Usuario de Prueba',
                    'test@example.com',
                    'Email de Prueba',
                    'Este es un mensaje de prueba desde el comando artisan.'
                )
            );

            $this->info('✅ Email sent successfully!');

        } catch (\Exception $e) {
            $this->error('❌ Error sending email: ' . $e->getMessage());
            $this->error('Full error: ' . $e->getTraceAsString());
        }
    }
}
