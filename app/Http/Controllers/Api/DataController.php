<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Services\AppData;

class DataController extends Controller
{
    public function getArticlesAddedData()
    {
        return array_flatten(AppData::getArticlesAddedCount());
    }

    public function getUserCount()
    {
        return AppData::userCount();
    }
}
