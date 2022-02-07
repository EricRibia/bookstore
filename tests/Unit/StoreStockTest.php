<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreStockTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_validation_for_new_stock_creation()
    {
        $this->json('POST', 'api/stock', [
        ])->assertUnprocessable(422);
    }
    public function test_create_new_stock_successfully()
    {
        $author = Author::factory()->create();

        $this->json('POST', 'api/stock', [
            'author_id' => 1000,
            'title' => 'New Book',
            'year_of_publication' => '2020-10-10',
            'description' => 'Zoology and Botany',
            'quantity' => '5',
        ])->assertStatus(404);

        $this->json('POST', 'api/stock', [
            'author_id' => $author->id,
            'title' => 'New Book',
            'year_of_publication' => '2020-10-10',
            'description' => 'Zoology and Botany',
            'quantity' => '5',
        ])->assertSuccessful();
    }
}
