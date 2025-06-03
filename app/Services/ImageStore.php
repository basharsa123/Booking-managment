<?php

namespace App\Services;



class ImageStore
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function store($request)
    {
        // upload the image file if there is an image
        if ($request->hasFile("image")) {
            $file = $request->file('image');
            $file_name = time() . '_' . $file->getClientOriginalName();
            $file_path = $file->storeAs('events', $file_name);
            return $file_path;
        }
        return null;
    }
}
