<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
            'created_at' => 'created_at',
            'name' => 'name',
            'stocks' => 'stocks',
        ];

        $model = new Product();

        $query = Product::when($request->get('search'), function ($query) use ($model, $request) {
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
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        $model = new Product();
        $model->fill($request->validated());

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
    public function show(string $id)
    {
        $model = Product::findOrFail($id);

        return $this->responseJsonData($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $model = Product::findOrFail($id);

        DB::beginTransaction();
        $model->fill($request->validated());

        try {
            $model->save();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseJsonMessageCrud(false, "edit", null, $th->getMessage());
        }

        DB::commit();

        return $this->responseJsonMessageCrud(true, "edit");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Product::findOrFail($id);

        DB::beginTransaction();

        try {
            $model->delete();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseJsonMessageCrud(false, "delete", null, $th->getMessage());
        }

        DB::commit();

        return $this->responseJsonMessageCrud(true, "delete");
    }
}
