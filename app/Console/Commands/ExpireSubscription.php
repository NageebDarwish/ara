<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;

class ExpireSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire-subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire and delete subscriptions that are not renewable and have ended.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = Subscription::where('is_renewable', 0)
            ->where('end_date', '<=', now())
            ->whereHas('plan', function($q){
                $q->where('is_default',0);
            })
            ->get();

        $subscriptions->each->delete();

        $this->info('Expired subscriptions deleted successfully.');
    }
}
