<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class DeleteRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:delete-role {roleName}';
  
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a role';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $roleName = $this->argument('roleName');

        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $role->delete();
            $this->info("Role '$roleName' deleted successfully.");
        } else {
            $this->error("Role '$roleName' not found.");
        }
    }
}
