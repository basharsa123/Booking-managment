<?php

namespace App\Console\Commands;

use App\Jobs\runrepo;
use App\Jobs\Testing_queue_worker;
use App\Mail\SendReminder;
use App\Models\event;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Pipeline;

class testings_for_queues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:testings_for_queues';

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
        Testing_queue_worker::dispatch()->onQueue("tests");

        // queues workflows types :
        //tests for chain
//        $chain = [
//            // if any one doesn't work properly stop the chain array
//            new Testing_queue_worker($user),
//            new runrepo("to repo go"),
//        ];
//        Bus::chain($chain)
//            ->catchCallbacks() // to send an error message if it stopped
//        ->then(function(){
//            logger("the batch process is passed");
//            }) // to send message of succeeded process
//        ->onQueue()
//        ->onConnection()
//            ->dispatch();

        //tests for batch
//        $batch = [
//            // if any one doesn't work properly no matter
//            new Testing_queue_worker(),
//            new runrepo("to repo go!"),
//        ];
//        Bus::batch($batch)
//            ->catch(function ($batch , $e) {
//                logger("the error is occurred : " . $e);
//            })
//            ->then(function(){
//            logger("the batch process is passed");
//            }) // to send message of succeeded process
//        ->onQueue("default")
//        ->onConnection("database")
//        ->dispatch();
    }
}
