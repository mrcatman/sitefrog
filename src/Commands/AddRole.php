<?php

namespace Sitefrog\Commands;

use Illuminate\Console\Command;
use Sitefrog\Models\User;
use Spatie\Permission\Models\Role;

class AddRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitefrog:role:add {user_id_or_email} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::find($this->argument('user_id_or_email'));
        if (!$user) {
            $user = User::where(['email' => $this->argument('user_id_or_email')])->first();
        }

        if (!$user) {
            $this->components->error('User not found');
            return 0;
        }

        $user->assignRole($this->argument('role'));


    }
}
