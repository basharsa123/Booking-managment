<?php

namespace App\Policies;

use App\Models\User;
use App\Models\book;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class BookPolicy
{

    public function is_creator_of_book(User $user, book $book)
    {
        return $user->id === $book->user->id;
    }

}
