<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        $latest = Transaction::orderByDesc('id')->first();

        if ($latest) {
            $LAST_CODE = explode('-', $latest->code)[0];
            $LAST_NUMBER = (int) explode('-', $latest->code)[1];
            $code = $LAST_CODE . "-" . str_pad($LAST_NUMBER + 1, 6, "0", STR_PAD_LEFT);
        } else {
            $code = "TRX-" . "000001";
        }

        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $product = $products->random();
            $quantity = rand(1, 10);

            if ($i != 0) {
                $LAST_CODE = explode('-', $code)[0];
                $LAST_NUMBER = (int) explode('-', $code)[1];
                $code = $LAST_CODE . "-" . str_pad($LAST_NUMBER + 1, 6, "0", STR_PAD_LEFT);
            }

            $data[] = [
                'code' => $code,
                'price' => $product->price,
                'quantity' => $quantity,
                'amount' => $product->price * $quantity,
                'product_id' => $product->id,
            ];
        }

        DB::table('transactions')->insert($data);
    }
}
