<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class ExportProductToAmazon
{
    public function handle(Collection|array $produtos)
    {
        if ($produtos instanceof Collection) {
            $produtos = $produtos->toArray();
        }

        Http::post(
            'https://api.amazon.com/products',
            array_map(fn($p) => ['title' => $p['title']], $produtos),
        );
    }
}
