<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateStockTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_validation_for_update_of_new_stock()
    {
        $this->json('PUT', 'api/stock/1', [
            'type' => 'add',
            'quantity' => 5,
        ])->assertStatus(404);

        $author = Author::factory()->create();
        $book = Book::factory()->create(
            ['author_id' => $author->id, 'quantity' => 10]
        );

        $this->json('PUT', 'api/stock/' . $book->id, [
            'type' => 'test_type',
            'quantity' => 5,
        ])->assertUnprocessable(422);

    }
    public function test_addition_and_reduction_of_stock_with_quantity_validation()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(
            ['author_id' => $author->id, 'quantity' => 10]
        );

        $this->json('PUT', 'api/stock/' . $book->id, [
            'type' => 'add',
            'quantity' => 5,
        ])->assertSuccessful();

        $this->json('PUT', 'api/stock/' . $book->id, [
            'type' => 'reduce',
            'quantity' => 100,
        ])->assertStatus(400);

        $this->json('PUT', 'api/stock/' . $book->id, [
            'type' => 'reduce',
            'quantity' => 10,
        ])->assertStatus(200);

    }
}
