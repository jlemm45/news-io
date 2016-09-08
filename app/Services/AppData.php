<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AppData {

    public static function getArticlesAddedCount()
    {
        return DB::table('articles')
            ->select(DB::raw('COUNT(1) as count, created_at'))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%yyyy%mm%dd")'))
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get();
        //DB::raw('SELECT created_at, COUNT(1) as \'count\' FROM articles GROUP BY DATE_FORMAT(created_at, "%yyyy%mm%dd") order by id desc limit 100');
    }

    public static function userCount()
    {
        return DB::table('users')->count();
    }
    
}