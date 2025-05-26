<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\BookController;
use App\Models\event;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
//? it's all done (-_0)
Route::group(
    [
        "middleware" => "api",
        "prefix" => "auth"
    ],
    function ($router)
    {
        Route::post('register',  [AuthController::class, 'register'])->name("register");
        Route::post('login', [AuthController::class, 'login'])->name("login");
        Route::get('me', [AuthController::class, 'me'])->name("me")->middleware("auth") ;
        Route::delete('logout', [AuthController::class, 'logout'])->name("logout")->middleware("auth");
    }
);
//? it's all done (-_0)
Route::group(
    [
        "middleware" => "api",
        "prefix" => "event"
    ],
    function ($router)
    {
        // little dumpy example for caching
        Route::get("/testing_cache" , function(){
            $events = Cache()->remember("event" ,10 , function() {
                return event::with("user")->get();
            });
            $time = \Illuminate\Support\Facades\Cache::remember("time" ,5 , function() {
               return  now()->format("H:i:s");
            });
            return $events->first()->title;
        });
        Route::get("/index", [EventController::class, "index"])->name("event.index");
        Route::get("/show/{id}", [EventController::class, "show"])->name("event.show");
        Route::post("/create", [EventController::class, "store"])->name("event.store")->middleware("auth");
        Route::post("/update/{id}", [EventController::class, "update"])->name("event.update")->middleware("auth");
        Route::delete("/delete/{id}", [EventController::class, "destroy"])->name("event.destroy")->middleware("auth");
    }
);
//? it's all done (-_0)
Route::group(
    [
        "middleware" => "api",
        "prefix" => "book"
    ],
    function ($router)
    {
        Route::get("/index", [BookController::class, "show"])->name("book.show")->middleware("auth");
        Route::post("/create", [BookController::class, "store"])->name("book.store")->middleware("auth");
        Route::delete("/delete/{book}", [BookController::class, "destroy"])->name("book.destroy")->middleware("auth" );
    }
);
Route::fallback(function () {
    return response()->json([
        'message' => 'Oops! This route does not exist.',
    ], 404);
});
