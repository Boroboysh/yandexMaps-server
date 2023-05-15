<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default admin';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Admin::create([
            'name' => 'admin1',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin')
        ]);

        return Command::SUCCESS;
    }
}
