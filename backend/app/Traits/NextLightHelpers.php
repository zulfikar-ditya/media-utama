<?php

namespace App\Traits;

trait NextLightHelpers
{
    // =========================>
    // ## For remark column
    // =========================>
    protected function remark_column($column, $remarks)
    {
        return isset($remarks[$column]) ? $remarks[$column] :  $column;
    }

    // =========================>
    // ## For filter
    // =========================>
    protected function filter($column, $control, $model, $query = null)
    {
        $type = explode(':', $control)[0];
        $value = explode(':', $control)[1];
        $expColumn = explode('.', $column);
        $queryDump = !is_null($query) ? $query : $model;

        if ($type) {
            // =========================>
            // ## Equal operator
            // =========================>
            if ($type == 'eq') {
                if (count($expColumn) > 1) {
                    $queryDump = $queryDump->join($expColumn[0], function ($join) use ($model, $expColumn, $value) {
                        $join->on($model->getTable() . ".id", "$expColumn[0]." . substr($model->getTable(), 0, -1) . "_id")
                            ->where("$expColumn[0].$expColumn[1]", $value);
                    });
                } else {
                    $queryDump = $model->where($column, $value);
                }

                // =========================>
                // ## Not equal operator
                // =========================>
            } else if ($type == 'ne') {
                if (count($expColumn) > 1) {
                    $queryDump = $queryDump->join($expColumn[0], function ($join) use ($model, $expColumn, $value) {
                        $join->on($model->getTable() . ".id", "$expColumn[0]." . substr($model->getTable(), 0, -1) . "_id")
                            ->where("$expColumn[0].$expColumn[1]", '!=', $value);
                    });
                } else {
                    $queryDump = $queryDump->where($column, '!=', $value);
                }

                // =========================>
                // ## In operator
                // =========================>
            } else if ($type == 'in') {
                if (count($expColumn) > 1) {
                    $queryDump = $queryDump->join($expColumn[0], function ($join) use ($model, $expColumn, $value) {
                        $join->on($model->getTable() . ".id", "$expColumn[0]." . substr($model->getTable(), 0, -1) . "_id")
                            ->whereIn("$expColumn[0].$expColumn[1]", explode(',', $value));
                    });
                } else {
                    $queryDump = $queryDump->whereIn($column, explode(',', $value));
                }

                // =========================>
                // ## Not in operator
                // =========================>
            } else if ($type == 'ni') {
                if (count($expColumn) > 1) {
                    $queryDump = $queryDump->join($expColumn[0], function ($join) use ($model, $expColumn, $value) {
                        $join->on($model->getTable() . ".id", "$expColumn[0]." . substr($model->getTable(), 0, -1) . "_id")
                            ->whereNotIn("$expColumn[0].$expColumn[1]", explode(',', $value));
                    });
                } else {
                    $queryDump = $queryDump->whereNotIn($column, explode(',', $value));
                }

                // =========================>
                // ## Between operator
                // =========================>
            } else if ($type == 'bw') {
                if (count($expColumn) > 1) {
                    $queryDump = $queryDump->join($expColumn[0], function ($join) use ($model, $expColumn, $value) {
                        $join->on($model->getTable() . ".id", "$expColumn[0]." . substr($model->getTable(), 0, -1) . "_id")
                            ->whereBetween("$expColumn[0].$expColumn[1]", explode(',', $value));
                    });
                } else {
                    $queryDump = $queryDump->whereBetween($column, explode(',', $value));
                }

                // =========================>
                // ## Not between operator
                // =========================>
            } else if ($type == 'nb') {
                if (count($expColumn) > 1) {
                    $queryDump = $queryDump->join($expColumn[0], function ($join) use ($model, $expColumn, $value) {
                        $join->on($model->getTable() . ".id", "$expColumn[0]." . substr($model->getTable(), 0, -1) . "_id")
                            ->whereNotBetween("$expColumn[0].$expColumn[1]", explode(',', $value));
                    });
                } else {
                    $queryDump = $queryDump->whereNotBetween($column, explode(',', $value));
                }
            }
        }

        return $queryDump;
    }

    // =========================>
    // ## For search
    // =========================>
    protected function search($keyword, $model, $query = null)
    {
        $model = (!is_null($query) ? $query : $model)->where(function ($query) use ($keyword, $model) {
            foreach ($model->searchable as $search_column) {
                $expColumn = explode('.', $search_column);

                if (count($expColumn) > 1) {
                    $query->join($expColumn[0], function ($join) use ($model, $expColumn, $keyword) {
                        $join->on("$model->getTable().id", "$expColumn[0]." . substr($model->getTable(), 0, -1) . "_id")
                            ->orWhere("$expColumn[0].$expColumn[1]", 'LIKE', "%" . $keyword . "%");
                    });
                } else {
                    $query->orWhere($search_column, 'LIKE', "%" . $keyword . "%");
                }
            }
        });

        return $model;
    }

    // =========================>
    // ## For dumping field
    // =========================>
    protected function dump_field($request, $model)
    {
        foreach ($model->getFillable() as $key_field) {
            isset($request[$key_field]) && $model->setAttribute($key_field, $request[$key_field]);
        }

        return $model;
    }
}
