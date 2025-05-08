<?php

namespace App\Http\Controllers;

use App\Events\BookingCreated;
use App\Models\book;
use App\Models\event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookController extends Controller
{
    /**
     * Display the books for specified user .
     */
    public function show()
    {
//        return response()->json(Auth::user()->id, 201);
        if(Auth::check()) {
            try {
                $user_books = User::find(Auth::user()->id)->books; // is an array
                return response()->json($user_books, 201);
            } catch (\Exception $exception) {
                return view($exception->getMessage(), 402);
            }
        }
        return response()->json("Unauthorized", 401);
    }
    /**
     * Store a book for User
     */

    public function store(Request $request )
    {
        BookingCreated::dispatch($request);


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
//            event::checkSlotAndBook($request->input("event_id") , event::find( request('event_id'))->capacity);


            //?register a book
            book::create([
                "user_id" => Auth::user()->id,
                "event_id" => $credentials['event_id'],
                "registered_at" => $credentials['registered_at'],
                "status" => $credentials['status'],
                "attendance" => $credentials['attendance'],
            ]);
        }catch (\Exception $e){
            return response( $e->getMessage(), 422);
        }
        return response("Book added successfully", 201);
    }
    /**
     * Remove the specified book from your books.
     */
    public function destroy($id)
    {
        //? delete the book
        try{
            $book = book::find($id);
//            event::dropTheSlot($book->id);
            $book->delete();
        }catch(\Exception $e){
            return response($e->getMessage(), 422);
        }
        return response("Book deleted successfully", 201);
    }
}
