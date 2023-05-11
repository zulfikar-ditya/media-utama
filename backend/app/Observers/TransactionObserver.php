<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "creating" event.
     */
    public function creating(Transaction $transaction): void
    {
        $latest = Transaction::orderByDesc('id')->first();

        if ($latest) {
            $LAST_CODE = explode('-', $latest->code)[0];
            $LAST_NUMBER = (int) explode('-', $latest->code)[1];
            $code = $LAST_CODE . "-" . str_pad($LAST_NUMBER + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $code = "TRX-" . "000001";
        }

        $transaction->code = $code;
    }

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
