<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Author;
use App\Models\Book;
use App\Models\StockHistory;
use App\Models\StockStatus;
use Illuminate\Http\Request;

class StockController extends BaseController
{
    public function index(Request $request)
    {
        $attributes = $request->validate([
            'year_of_publication' => 'nullable|date_format:Y',
            'author' => 'nullable|string',
        ]);

        $books = Book::booksWithTheirAuthors($attributes);

        $books = $this->AddStockStatusToStock($books, 'quantity');
        $msg = sprintf('Available books is %d', $books->count());

        $stock_status = StockStatus::where('min', '>=', 7)
            ->where('max', '<', 7)->get();

        return $this->sendSuccess($msg, $stock_status);
    }

    public function stockHistory($id)
    {
        $book = Book::findOrFail($id);

        $msg = sprintf("Stock history for %s", $book->title);

        $data = StockHistory::getStockHistory($id);

        return $this->sendSuccess($msg, $data);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'author_id' => 'required|integer',
            'title' => 'required|unique:books|max:255',
            'year_of_publication' => 'required|date',
            'description' => 'required|max:1000',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $author = Author::findOrFail($attributes['author_id']);

        unset($attributes['author_id']);
        $response = $author->addNewStockToAuthor($attributes);

        $message = sprintf("New stock for %s successfully added.", $author->first_name);

        return $this->sendSuccess($message, $response);
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->validate([
            'type' => 'required|in:add,reduce',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $book = Book::findOrFail($id);

        $add_or_reduce = $attributes['type'] === 'add' ? $attributes['quantity'] : -$attributes['quantity'];
        $add_or_reduce_message = $attributes['type'] === 'add' ? 'added' : 'reduced';

        $validity = $book->validateNewQuantity($add_or_reduce);

        if ($validity->failed) {
            return $this->sendFailure($validity->message);
        }

        $book->updateBookStock($validity->new_quantity);

        $message = sprintf("Stock successfully %s", $add_or_reduce_message);

        return $this->sendSuccess($message);
    }

    private function AddStockStatusToStock($data, $quantity_column)
    {

        foreach ($data as $dt) {
            $dt['stock_status'] = StockStatus::getStockStatus($dt[$quantity_column]);
        }
        return $data;

    }
}
