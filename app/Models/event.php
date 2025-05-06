<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

class event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        "title",
        "description",
        "date",
        "image",
        "capacity",
        "created_by"
    ];
    private static array $slots = [];
    public static function setSlots($id , $value)
    {
        self::$slots[$id] = $value;
    }

    public function checkSlotAndBook($event_id , $event){
        if(self::$slots["event_id"])
        {
            self::$slots["event_id"]--;
        }
        return response()->json(["no enough slots available for this event", 400]);
    }
    public function dropTheSlot($event_id){
        if(self::$slots["event_id"]) {
            self::$slots["event_id"] = 0;
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class );
    }

}
