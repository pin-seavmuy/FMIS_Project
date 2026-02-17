<?php

namespace App\Traits;

use App\Models\User;
use App\Services\UserService;

trait IncomeTrait {
    public function report(array $filter){
        $report = User::all();
        return $report;
    }

    public function UserReport(UserService $service, $search = null){
        return $service->list($search);
    }
}