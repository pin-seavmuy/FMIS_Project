<?php

namespace App\Services;

use App\Models\ChartOfAccount;

class ChartOfAccountService extends BaseService
{
    public function __construct(ChartOfAccount $model)
    {
        parent::__construct($model);
    }

    public function getTree()
    {
        return $this->model->with('children')->whereNull('parent_id')->get();
    }
    
    public function all()
    {
        return $this->model->orderBy('code')->get();
    }

    public function getFlatList()
    {
        return $this->all();
    }

    public function search(string $query)
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->orderBy('code')
            ->get();
    }
}
