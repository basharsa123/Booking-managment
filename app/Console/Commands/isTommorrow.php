<?php

namespace App\Console\Commands;

use App\Models\event;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class isTommorrow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:isTomorrow';

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

        $books =\App\Models\book::all();
        foreach ($books as $book) {
//            $this->comment($book->event->title);
//                $this->comment($book->event->date); // to sure that
                 $check = (\Carbon\Carbon::parse($book->event->date)->isToday());
//            $this->comment($book->event->date); // to sure that
            if ($check) {
                    $this->comment("sendNotification");
            }
        }
    }
}
