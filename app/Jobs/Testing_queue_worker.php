<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Testing_queue_worker implements ShouldQueue , ShouldBeUnique ,ShouldBeUniqueUntilProcessing
{
    use Queueable, SerializesModels , Batchable , InteractsWithQueue ,Dispatchable;
//    protected $tries = 10; // number of failed tries
//
//    protected $maxExceptions = 2; // number of exceptions allows before queue stops
//
//    public $queue = 'name_of_queue'; //name of queue
//
//    protected $backoff = [2 , 5 , 20]; //retry the queue after 2 then if it failed after 5 then after 20
//
//    public $delay = 300; // seconds before the worker start
//
//    public $shouldBeEncrypted = true; // to encrypt the queue worker
//
//    public $connection = 'redis'; // where the pending queues stored
//
//    protected $timeout = 12; //seconds of tries till worker
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //? close the worker  for 15 seconds to running that properly
        //?by using caching
//        Cache::lock("testing_queue_worker")->block( 15 ,  function()  {
//            info("start ");
//
//            sleep(5);
//
//            info("finished ");
//        });
//        ? by using redis
//        Redis::throttle("testing_queue_worker")
//        ->limit(5)
//        ->block( 15)
//        ->then(function () {
//            // block of code
//            info("start ");
//
//            sleep(5);
//
//            info("finished ");
//
//        });
//
//        Redis::funnel("testing_queue_worker")
//        ->allow()
//            ->every()
//            ->block()
//            ->then(function()  {
//                info("start ");
//
//                sleep(5);
//
//                info("finished ");
//
//            });
    }

    /**
     * Create a new job instance.
     */
//    public function uniqueId() // don't send same queue of the returned name till again
//    {
//        return "testing_queue_worker";
//    }

//    public function uniqueFor() //do not send same queue for 60 second
//    {
//    return 60;
//    }

//      public function retryUntil() //retry the queues till the returned value
//      {
//        return now()->addSeconds(10);
//      }
//    public function failed(\Exception $e) //to send the message for the failed queues
//    {
//        info("Failed to send notification ". $e->getMessage());
//    }

//    public function middleware() // not sending any similar request till 10 seconds
//    {
//        return [
//            new WithoutOverlapping("testing_queue_worker" , 10)
//        ];
//    }
}
