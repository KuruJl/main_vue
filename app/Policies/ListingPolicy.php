<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ListingPolicy
{
    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Auth\Access\Response|bool // <-- ИЗМЕНИТЕ ЭТУ СТРОКУ!
     */
    public function update(User $user, Listing $listing): Response|bool // <-- И ЭТУ СТРОКУ!
    {
        // Пользователь может обновлять объявление, если он является его владельцем.
        return $user->id === $listing->user_id
               ? Response::allow()
               : Response::deny('Вы не являетесь владельцем этого объявления.');
    }

    /**
     * Determine whether the user can delete the model.
     * Если у вас есть метод destroy, добавьте политику для него
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Auth\Access\Response|bool // <-- ИЗМЕНИТЕ ЭТУ СТРОКУ!
     */
    public function delete(User $user, Listing $listing): Response|bool // <-- И ЭТУ СТРОКУ!
    {
        return $user->id === $listing->user_id
               ? Response::allow()
               : Response::deny('Вы не являетесь владельцем этого объявления.');
    }

    // Если у вас есть другие действия (например, просмотр, создание), можете добавить их сюда
    // public function view(User $user, Listing $listing): Response|bool { /* ... */ }
    // public function create(User $user): Response|bool { /* ... */ }
}