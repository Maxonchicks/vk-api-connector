<?php


namespace App;

use App\Models\VkData;
use Illuminate\Support\Facades\Http;

class VkAPI
{
    public function callAPI($id_group)
    {
        $responsePost = Http::get(config('services.vk.url').'wall.get',
        [
            'owner_id' => '-'.$id_group,
            'access_token' => config('services.vk.token'),
            'v' => config('services.vk.version'),
            'count' => 5,
        ]);

        $aboutPost = $responsePost->json();



        foreach ($aboutPost['response']['items'] as $item)
        {
            $responseLikes = Http::get(config('services.vk.url').'likes.getList',
                [
                    'type' => 'post',
                    'owner_id' => '-'.$id_group,
                    'item_id' => $item['id'],
                    'count' => $item['likes']['count'],
                    'access_token' => config('services.vk.token'),
                    'v' => config('services.vk.version'),
                ]);
            $dataLikes = $responseLikes->json();

            foreach ($dataLikes['response']['items'] as $like)
                if (!VkData::where('post_published_dtm', date('Y-m-d H:i:s', $item['date']))->where('event_type', 'likes')->where('user_id', $like)->exists())
                    {
                        VkData::create([
                            'group_name' => "Perviy MIETovskiy",
                            'group_id' => '-'.$id_group,
                            'post_published_dtm' => date('Y-m-d H:i:s', $item['date']),
                            'event_type' => 'likes',
                            'user_id' => $like,
                        ]);
                    }
        }



        foreach ($aboutPost['response']['items'] as $item)
        {
            $responseComments = Http::get(config('services.vk.url').'wall.getComments',
                [
                    'owner_id' => '-'.$id_group,
                    'post_id' => $item['id'],
                    'count' => $item['comments']['count'],
                    'access_token' => config('services.vk.token'),
                    'v' => config('services.vk.version'),
                ]);

            $dataComments = $responseComments->json();

            foreach ($dataComments['response']['items'] as $comments)
                if (!VkData::where('post_published_dtm', date('Y-m-d H:i:s', $item['date']))->where('event_type', 'comments')->where('user_id', $comments['from_id'])->exists())
                {
                    VkData::create([
                        'group_name' => "Perviy MIETovskiy",
                        'group_id' => '-'.$id_group,
                        'post_published_dtm' => date('Y-m-d H:i:s', $item['date']),
                        'event_type' => 'comments',
                        'user_id' => $comments['from_id'],
                    ]);
                }
        }

    }
}