<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Company;

class PostRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = User::all();
        foreach ($users as $user) {
            // Post rate = (number of posted gigs / number of all shifts) * 100
            $postedGigs = 0;
            $shifts = 0;

            $companies = Company::all()->where('user_id', $user->id);
            foreach ($companies as $company) {
                $postedGigs += $company->getTotalGigs();
                $shifts += $company->getStartedGigs();
            }
            if (!empty($postedGigs) and !empty($shifts)) {
                $postRate = ($postedGigs / $shifts)  * 100;
                $user->post_rate = $postRate;
                $user->save();
            }
        }
        echo "All done" . PHP_EOL;
        return 0;
    }
}
