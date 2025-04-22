<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\User;
use App\Notifications\NewProductNotification;

class CreateProductAction
{
    public function handle(User $user, string $title, string $code, bool $released)
    {
        Product::query()->create([
            'owner_id' => $user->id,
            'title' => $title,
            'code' => $code,
            'released' => $released,
        ]);
        $user->notify(new NewProductNotification());
    }
}
