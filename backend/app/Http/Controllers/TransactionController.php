<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortDirection = $request->get('sortDirection', 'DESC');
        $sortby = $request->get('sortBy', 'created_at');
        $paginate = $request->get('paginate', 10);
        $filter = $request->get('filter', null);

        $columnAliases = [
            'code', 'price', 'quantity', 'amount',
        ];

        $model = new Transaction();

        $query = Transaction::when($request->get('search'), function ($query) use ($model, $request) {
            $this->search($request->get('search'), $model, $query);
        });

        if ($filter) {
            $filters = json_decode($filter);
            foreach ($filters as $column => $value) {
                $query = $this->filter($this->remark_column($column, $columnAliases), $value, $query);
            }
        }

        $query = $query->orderBy($this->remark_column($sortby, $columnAliases), $sortDirection)
            ->select($model->selectable)
            ->paginate($paginate);

        if (empty($query->items())) {
            return response([
                'message' => 'empty data',
                'data' => [],
            ], 200);
        }

        return response([
            'message' => 'Success',
            'data' => $query->all(),
            'total_row' => $query->total()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        DB::beginTransaction();

        $product = Product::find($request->product_id);

        $model = new Transaction();
        $model->fill($request->all());
        $model->amount = $product->price * $request->quantity;
        $model->product()->associate($product);
        $model->price = $product->price;

        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseJsonMessageCrud(false, "create", null, $th->getMessage());
        }

        DB::commit();

        return $this->responseJsonMessageCrud(true, "create");
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
