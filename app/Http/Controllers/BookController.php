<?php

namespace App\Http\Controllers;

use App\Events\BookingCreated;
use App\Http\Requests\CreateBookRequest;
use App\Http\Resources\ShowBookController;
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
                return response()->json(ShowBookController::collection($user_books), 201);
            } catch (\Exception $exception) {
                return view($exception->getMessage(), 402);
            }
        }
        return response()->json("Unauthorized", 401);
    }
    /**
     * Store a book for User
     */

    public function store(CreateBookRequest $request )
    {

//        BookingCreated::dispatch($request);
//        return response()->json($request, 201);

        $credentials = $request->validated();
        try{
            if (!Auth::check()) {
                return response("Unauthorized", 401);
            }
            $credentials["user_id"] = Auth::user()->id;
            $credentials["event_id"] = $request->event_id;
            $credentials["registered_at"] = now()->format('Y-m-d H:i:s');
            // ? check if the event capacity and decrease it by one
            // event::checkSlotAndBook($request->input("event_id") , event::find( request('event_id'))->capacity);


            //?register a book
            book::create($credentials);
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
