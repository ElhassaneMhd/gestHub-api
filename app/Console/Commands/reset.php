<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting database...');
        $this->call('migrate:fresh'); // Reset schema and run migrations

        $this->info('Running database migrations...');
        $this->call('migrate');

        $this->info('Seeding database with sample data...');
        $this->call('db:seed');

        $this->info('app reseted  successfully!');
        $folderName = 'attestation';
        $path = public_path($folderName);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
            $this->info("Directory '$folderName' created in public folder.");
        }
        return 0;
    }
}
