<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Services\AppData;

class DataController extends Controller
{
    public function getArticlesAddedData()
    {
        //print_r(AppData::getArticlesAddedCount());
        return array_flatten(AppData::getArticlesAddedCount());
    }
}
