<?php

namespace App\Models;

use App\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    protected $fillable =[
        "user_id",
        "event_id",
        "registered_at",
        "status",
        "attendance",
        "notes",
    ];
    public $casts = [
        "status" => Status::class ,
    ];
    public function event()
    {
        return $this->belongsTo(event::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
