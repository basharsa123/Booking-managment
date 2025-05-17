<?php

namespace App\Events;

use App\Models\book;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class BookingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct($request)
    {
        try{
            if (!Auth::check()) {
                return response("Unauthorized", 401);
            }

            $credentials = $request->validate([
                "event_id" => ["required"],
                "status" => ["required"],
            ]);
            $credentials['event_id'] = request('event_id');
            $credentials['registered_at'] = now();
            $credentials["attendance"] = $request->attendance ?? 0;
            // ? check if the event capacity and decrease it by one
            //  event::checkSlotAndBook($request->input("event_id") , event::find( request('event_id'))->capacity);

            //?register a book
            book::create([
                "user_id" => Auth::user()->id,
                "event_id" => $credentials['event_id'],
                "registered_at" => $credentials['registered_at'],
                "status" => $credentials['status'],
                "attendance" => $credentials['attendance'],
            ]);
        }catch (\Exception $e){
            return $e->getMessage();
        }
        return 1;

    }
}
