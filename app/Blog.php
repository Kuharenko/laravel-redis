<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Blog extends Model
{
    //
    protected $table = 'blog';
    protected $fillable = ['id', 'title', 'text', 'slug', 'author'];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            Blog::updateRedisInfo($model);
        });

        self::updated(function ($model) {
            Blog::updateRedisInfo($model);
        });

        self::deleted(function ($model) {
            Blog::deleteRedisInfo($model);
        });

    }

    public static function updateRedisInfo($model)
    {
        Redis::pipeline(function ($pipe) use ($model) {
            $pipe->set("posts:{$model->id}", json_encode($model));
            $pipe->hSet("posts:lookup:slug", $model->slug, $model->id);
        });
        Blog::updatePostPaginate();
    }

    public static function deleteRedisInfo($model)
    {
        Redis::pipeline(function ($pipe) use ($model){
            $pipe->del("posts:{$model->id}");
            $pipe->hDel("posts:lookup:slug", $model->slug);
        });
        Blog::updatePostPaginate();
    }

    public static function updatePostPaginate()
    {
        Redis::pipeline(function ($pipe) {
            $per_page = 15;
            $pager = Blog::paginate($per_page);

            for ($i = 1; $i <= $pager->lastPage(); $i++) {
                $data = Blog::paginate($per_page, ['*'], 'page', $i);
                $data->withPath(url('api/post'));
                $pipe->set("posts:page:{$i}", json_encode($data));
            }
        });
    }
}
