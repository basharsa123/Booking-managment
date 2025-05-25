<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\EventShowController;
use App\Http\Resources\ShowEventcontroller;
use App\Models\event;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Cache()->remember("event" , 60*60 , function() {
            return event::with("user")->get();
        });
        if(! $events->isEmpty()) {return response()->json(ShowEventcontroller::collection($events ) , 202   );}
        else { return response()->json("No Events Found" , 404);}
    }

    /**
     * Store a new Events.
     */


    public function store(CreateEventRequest $request)
    {
        try{
            $credentials = $request->validated();
            $credentials["user_id"] = Auth::user()->id;
            $credentials["created_by"] = Auth::user()->name;
            $credentials["description"] = $request->description ?? "No description";
            // upload the image file if there is an image
            if ($request->hasFile("image")) {
                $file = $request->file('image');
                $file_name = time() . '_' . $file->getClientOriginalName();
                $file_path = $file->storeAs('events', $file_name);
                $credentials["image"] = $file_path;
            }
            $event = event::create($credentials);
            Artisan::call("cache:clear");
            // creating the new event
            $slots[event::all()->last()->id] = $request['capacity'];
            return response()->json("Event created successfully", 201);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified Event.
     */
    public function show($id)
    {
        $event_find = Cache::remember("event-show" , 60*60 , function() use($id){
           return event::find($id);
        });
        if($event_find)
        {
        return response()->json($event_find , 200);
        }
        else
        {
            return response()->json("Event not found", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Artisan::call("cache:clear");
        // Find the event
        $event = Event::find($id);
        if (!$event) {
            return response()->json("Event not found", 404);
        }

        // Validate the request
        try {
            $credentials = $request->validate([
                "title" => "required|string|max:255",
                "description" => "required|string",
                "date" => "nullable|date",
                "image" => "image|max:2048",
                "capacity" => "integer|max:250",
            ]);

            // Prepare data for update
            $dataToUpdate = [
                "user_id" => $event->user_id,
                "title" => $credentials['title'],
                "description" =>$credentials['description'],
                "date" => $credentials['date'],
                "capacity" => $credentials['capacity'],
                "created_by" => $event->created_by,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($event->image) {
                    Storage::disk("public")->delete($event->image);
                }
                // Upload new image
                $file_name = time() . '_' . $request->file("image")->getClientOriginalName();
                $file_path = $request->file("image")->storeAs('events', $file_name, 'public');
                $dataToUpdate['image'] = $file_path;
            } else {
                // Keep old image if no new image is uploaded
                $dataToUpdate['image'] = $event->image;
            }

            // Update the event
            $event->update($dataToUpdate);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 422);
        }

        // Return success response
        return response()->json("Event updated successfully", 200);
    }

    /**
     * Remove the specified Event from storage.
     */
    public function destroy($id)
    {
        Artisan::call("cache:clear");
        $event = event::find($id);
        if(! Gate::allows("is_creator_of_event" , $event))
        {
            return response()->json("you are not allowed to delete an event since you are not its creator", 401);
        }
            try{
                //? delete the image file for the event
                $file_path = $event->image;
                Storage::disk("public")->delete($file_path);
                //? delete or truncate ??
                //? just delete
                $event->delete();
            }catch (\Exception $e){
                return response()->json($e->getMessage(), 422);
            }
            //? return verified message
            return response()->json("Event deleted successfully", 201);

    }
}
