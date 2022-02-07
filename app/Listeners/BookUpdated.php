<?php

namespace App\Listeners;

use App\Events\BookUpdated as BookUpdatedEvent;
use App\Models\StockHistory;

class BookUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BookUpdatedEvent $event)
    {
        $stock_history = StockHistory::where('book_id', $event->book->id)->latest()->first();

        if ($stock_history) {
            $quantity_in_out = $event->book->quantity - $stock_history->quantity_current;
            $operation = $quantity_in_out < 0 ? 'reduce' : 'add';
        } else {
            $operation = 'add';
            $quantity_in_out = $event->book->quantity;
        }
        StockHistory::create([
            'book_id' => $event->book->id,
            'operation' => $operation,
            'quantity_in_out' => abs($quantity_in_out),
            'quantity_current' => $event->book->quantity,
        ]);
    }
}
