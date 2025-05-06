<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\book;
use App\Models\event;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();
        event::factory(10)->create();
        $events = event::all();
        foreach($events as $event){
            event::setSlots($event->id , $event->capacity);
        }
        book::factory(50)->create();

    }
}
