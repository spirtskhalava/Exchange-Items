<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    /**
     * Determine if the given product can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function update(User $user, Product $product)
    {
         Log::info('User ID: ' . $user->id);
        Log::info('Product User ID: ' . $product->user_id);
        return $user->id == $product->user_id;
    }


    public function delete(User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }
}
