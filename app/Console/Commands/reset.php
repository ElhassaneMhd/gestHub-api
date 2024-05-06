<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

        return 0;
    }
}
