<?php

namespace App\Console\Commands;

use App\Events\BookingCreated;
use App\Mail\SendReminder;
use App\Models\book;
use App\Models\event;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

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
        $tomorrow = Carbon::tomorrow()->toDateTimeString();
        $events_tomorrow = event::where("date", $tomorrow)->get();

        //? the easiest ! but it send email more than one time for those who book for the same event twice or more

        //? $users_have_event_tomorrow = []; for dd();
        foreach ($events_tomorrow as $event_tomorrow) {
            $books =  book::where("event_id", $event_tomorrow->id)->get();
            foreach ($books as $book) {
            // $users_have_event_tomorrow[] = $book->user->email; for dd();

                // to send the reminder by email
                Mail::to($book->user->email)->send(
                    new SendReminder($event_tomorrow)
                );

            }
        }
        //  dd($users_have_event_tomorrow);





        //        //? the hardest way !
//        $users_have_event_tomorrow = [];
//        foreach ($events_tomorrow as $event_tomorrow) {
//            $users_have_event_tomorrow = array_merge($users_have_event_tomorrow, book::where("event_id", $event_tomorrow->id)->pluck("user_id")->toArray());
//        }
//        $users_have_event_tomorrow = array_unique($users_have_event_tomorrow);


        //! to send the reminder (by_email)
//        foreach ($users_have_event_tomorrow as $user) {
//            User::where("id", $user)->each(function ($user_event) use ($events_tomorrow, $tomorrow) {
//                foreach ($events_tomorrow as $event_tomorrow) {
//                    if ($event_tomorrow->date == $tomorrow) {
//                        Mail::to($user_event->email)->send(
//                            new SendReminder($event_tomorrow)
//                        );
//                    }
//                }
//            });
//        }

        //for test
        // dd($users_have_event_tomorrow , $events_tomorrow);

    }
}
