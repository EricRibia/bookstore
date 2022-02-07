<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\StockHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockHistoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_creates_history_entry_on_addition_of_new_books()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(
            ['author_id' => $author->id, 'quantity' => 10]
        );

        $history_entry = StockHistory::where('book_id', $book->id)->get();

        $this->assertEquals($history_entry->count(), 1);

        $book->update([
            'quantity' => 50,
        ]);
        $book->update([
            'quantity' => 5,
        ]);

        $history_entry = StockHistory::where('book_id', $book->id)->get();

        $this->assertEquals($history_entry->count(), 3);
    }
    public function test_can_get_history_of_stock()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(
            ['author_id' => $author->id, 'quantity' => 10]
        );

        $this->json('PUT', 'api/stock/' . $book->id, [
            'type' => 'add',
            'quantity' => 15,
        ])->assertSuccessful();

        $this->json('PUT', 'api/stock/' . $book->id, [
            'type' => 'reduce',
            'quantity' => 7,
        ])->assertSuccessful();

        $response = $this->get('/api/stock/history/' . $book->id);

        $data = $response['data'];

        $this->assertEquals(count($data), 3);

        // 10 + 15 - 8 = 18;
        $response->assertJsonFragment(['quantity_current' => 18]);

    }
}
