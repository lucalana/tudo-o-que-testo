<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-product-command {title?} {code?} {user?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $title = $this->argument('title');
        $code = $this->argument('code');
        $userId = $this->argument('user');

        if (!$userId) {
            $userId = $this->ask('Please provide a valid user id');
        }
        if (!$title) {
            $title = $this->ask('Please provide a product title');
        }

        Validator::make(
            ['title' => $title, 'code' => $code, 'user' => $userId,],
            [
                'title' => ['required'],
                'code' => ['required'],
                'user' => ['required', Rule::exists('users', 'id')],
            ]
        )->validate();

        Product::query()->create([
            'title' => $title,
            'code' => $code,
            'released' => true,
            'owner_id' => $userId,
        ]);

        $this->components->info('Product created');
    }
}
