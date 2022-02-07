<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\StockStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    private $statuses = [
        [
            'max' => null,
            'min' => 10,
            'message' => 'Good',
        ],
        [
            'max' => 10,
            'min' => 5,
            'message' => 'Bad',
        ],
        [
            'max' => 5,
            'min' => 1,
            'message' => 'Critical',
        ],
        [
            'max' => 0,
            'min' => null,
            'message' => 'Out of stock',
        ],
    ];
    public function run()
    {
        \App\Models\Author::factory(3)
            ->has(Book::factory()->count(3))
            ->create();

        foreach ($this->statuses as $status) {

            StockStatus::create([
                'max' => $status['max'],
                'min' => $status['min'],
                'message' => $status['message'],
            ]);

        }
    }
}
