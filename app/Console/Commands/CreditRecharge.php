<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class CreditRecharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:credit-recharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recharge user credit at the start of every month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::where('role_id', Role::PREMIUM)->update(['credit' => 40]);
        User::where('role_id', Role::REGULAR)->update(['credit' => 20]);

        $this->info('User credits recharged successfully.');
    }
}
