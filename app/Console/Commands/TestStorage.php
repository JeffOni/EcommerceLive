<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestStorage extends Command
{
    protected $signature = 'test:storage';
    protected $description = 'Test storage functionality';

    public function handle()
    {
        $this->info('Testing storage...');

        // Test content
        $content = 'Test content - ' . now();
        $fileName = 'test/test-' . time() . '.txt';

        // Test storage write
        try {
            $result = Storage::put($fileName, $content);
            $this->info("Storage::put result: " . ($result ? 'true' : 'false'));

            // Check if file exists
            if (Storage::exists($fileName)) {
                $this->info("File exists in storage");

                // Get file content
                $readContent = Storage::get($fileName);
                $this->info("Content read: " . $readContent);

                // Get file size
                $size = Storage::size($fileName);
                $this->info("File size: " . $size . " bytes");

                // Get full path
                $fullPath = storage_path('app/' . $fileName);
                $this->info("Full path: " . $fullPath);

                // Check if file exists in filesystem
                if (file_exists($fullPath)) {
                    $this->info("File exists in filesystem");
                } else {
                    $this->error("File does NOT exist in filesystem");
                }

                // Clean up
                Storage::delete($fileName);
                $this->info("File deleted");
            } else {
                $this->error("File does NOT exist in storage");
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

        $this->info('Test completed');
    }
}
