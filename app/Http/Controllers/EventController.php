<?php

namespace App\Http\Controllers;

use App\Models\event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class EventController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = event::all();
        if(! $events->isEmpty()) {return response()->json($events , 200);}
        else { return response()->json("No Events Found" , 404);}
    }

    /**
     * Store a new Events.
     */


    public function store(Request $request)
    {

//        return response()->json(Auth::user()->id ?? 1 , 200);
//         to validate the request
        try {
            $credentials = $request->validate([
                "title" => "required",
                "description" => "required",
                "date" => "required|date",
                "image" => "required|image|max:2048",
                "capacity" => "required|integer|max:250",
                "created_by" => "required",
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 422);
        }

        try {
            // upload the image file
            $file = $request->file('image');
            $file_name = time() . '_' . $file->getClientOriginalName();
            $file_path = $file->storeAs('events', $file_name, 'public');
            // creating the new event
            $event = event::create([
                "user_id" => Auth::user()->id ?? 1,
                "title" => $credentials['title'],
                "description" => $credentials['description'],
                "date" => $credentials['date'],
                "image" => $file_path,
                "capacity" => $credentials['capacity'],
                "created_by" => $credentials['created_by'],
            ]);
            $slots[event::all()->last()->id] = $credentials['capacity'];
            return response()->json("Event created successfully", 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified Event.
     */
    public function show($id)
    {
        $event_find = event::find($id);
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
        $event = event::find($id);
        //  return response()->json($event, 200);
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
