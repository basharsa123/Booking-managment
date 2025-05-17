<?php

namespace App\Jobs;

use http\Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use function Pest\Laravel\withMiddleware;

class Testing_queue_worker implements ShouldQueue , ShouldBeUnique
{
    use Queueable, SerializesModels , Batchable , InteractsWithQueue ,Dispatchable;
    protected $tries = 10;
    protected $maxExceptions = 2;
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //? close the request for 15 seconds to running the last request probably
        //?by using caching
        Cache::lock("testing_queue_worker")->block( 15 ,  function()  {
            info("start ");

            sleep(5);

            info("finished ");
        });
        //? by using redis
//        Redis::throttle("testing_queue_worker")
//        ->limit(5)
//        ->block( 15)
//        ->then(function () {
//            // block of code
//        });
//
        //? Redis::funnel("testing_queue_worker")
//        allow()->
//            every()->
//            block()->
//            then(function()  {
//            //block of code
//        });


    }
    protected $backoff = [2 , 5 , 20];
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct( User $user)
    {
        $this->user = $user;
    }
//    public function uniqueId()
//    {
//        return "testing_queue_worker";
//    }
//    public function uniqueFor()
//    {
//    return 60;
//    }
    public function failed(\Exception $e)
    {
        info("Failed to send notification ". $e->getMessage());
    }
//    public function middleware()
//    {
//        return [
//            new WithoutOverlapping("testing_queue_worker" , 10)// not sending any similar request till 10 seconds
//        ];
//    }
}
