<?php

namespace App\Http\Controllers;

use App\Models\VkData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VkDataController extends Controller
{
    public static function grouped_data(Request $request){
        $events = VkData::where('created_at', '>=', $request->start_date)->get();
        $grouped_events = DB::table('vk_data')
            ->selectRaw('group_id')
            ->selectRaw('group_name')
            ->selectRaw('SUM(CASE WHEN EVENT_TYPE = \'like\' THEN 1 ELSE 0 END) AS cnt_likes')
            ->selectRaw('SUM(CASE WHEN EVENT_TYPE = \'comment\' THEN 1 ELSE 0 END) AS cnt_comments')
            ->selectRaw('SUM(CASE WHEN EVENT_TYPE = \'share\' THEN 1 ELSE 0 END) AS cnt_reposts')
            ->selectRaw('COUNT(DISTINCT post_published_dtm) AS cnt_posts')
            ->groupBy('group_id', 'group_name')->get();

        return response()->json($grouped_events, 200);
    }

    public function test(){
        return VkData::where('created_at', '>=', '2024-04-07 15:00:00')->get();
    }
}
