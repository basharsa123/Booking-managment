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
use Illuminate\Support\Facades\Gate;


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
        $allow_slots =event::find($request->event_id)->capacity - book::where("event_id" , $request->event_id)->count();

//        BookingCreated::dispatch($request);
//        return response()->json($allow_slots, 201);

        $credentials = $request->validated();
        try{
            if (!Auth::check()) {
                return response("Unauthorized", 401);
            }
            $credentials["user_id"] = Auth::user()->id;
            $credentials["event_id"] = $request->event_id;
            $credentials["registered_at"] = now()->format('Y-m-d H:i:s');
            // ? check if the event capacity and decrease it by one
            if(!  $allow_slots >= 0)
            {
                return response()->json("sorry , the event slots is full ." ,201);
            }
            //?register a book
            book::create($credentials);
        }catch (\Exception $e){
            return response( $e->getMessage(), 422);
        }
        return response()->json("Book added successfully", 201);
    }
    /**
     * Remove the specified book from your books.
     */
    public function destroy($book)
    {

        //? delete the book
        try{
            $book = book::find($book);
            if(! Gate::allows("is_creator_of_book" , $book))
            {
                return response()->json("you are not allowed to delete that book you are not its creator", 401);
            }

            $book->delete();
        }catch(\Exception $e){
            return response($e->getMessage(), 422);
        }
        return response("Book deleted successfully", 201);
    }
}
