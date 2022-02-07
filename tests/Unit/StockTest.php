<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_retrieves_stock_successfully()
    {
        //creates 3 authors with each 3 books
        \App\Models\Author::factory(3)
            ->has(Book::factory()->count(3))
            ->create();

        $response = $this->get('/api/stock');

        $data = $response['data'];

        $expectedCount = 9;

        $this->assertEquals(count($data), $expectedCount);
    }

    public function test_retrieves_correct_count_of_stock()
    {
        $response = $this->get('/api/stock');

        $response->assertStatus(200);
    }

    public function test_books_have_stock_status_field()
    {
        $author = Author::factory()->create();

        Book::factory()->create([
            'author_id' => $author->id, 'quantity' => 20]);

        $response = $this->get('/api/stock');

        $response->assertJsonFragment(['stock_status' => 'status unknown']);
    }

    public function test_filter_stock_by_author()
    {
        $authors = ['Eric', 'JamboPay', 'Anne', 'Claire', 'Margaret', 'Liz'];

        foreach ($authors as $author) {
            $author = Author::factory()->create([
                'first_name' => $author,
            ]);
            Book::factory()->create([
                'author_id' => $author->id,
            ]);
        }

        $response = $this->call('GET', '/api/stock', ['author' => 'Eric'])
            ->assertSuccessful();

        $data = $response['data'];

        $expectedCount = 1;

        $this->assertEquals(count($data), $expectedCount);
        $response->assertJsonFragment(['first_name' => 'Eric']);
    }

    public function test_filter_stock_by_year_of_publication()
    {

        $years_of_publication = ['2002', '2004', '2005', '2010', '2010', '2006'];

        //test authors
        $author = Author::factory()->create();

        foreach ($years_of_publication as $year) {
            Book::factory()->create([
                'author_id' => $author->id,
                'year_of_publication' => date('Y-m-d', strtotime($year . '-05-' . '05')),
            ]);
        }

        $response = $this->call('GET', '/api/stock', ['year_of_publication' => '2010'])
            ->assertSuccessful();

        $data = $response['data'];

        $expectedCount = 2;

        // dd($data);
        $this->assertEquals(count($data), $expectedCount);
    }
}
