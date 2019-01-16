<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;

class BlogController extends Controller
{

    public function index()
    {
        $page = Input::get('page')?:1;
        $posts = json_decode(Redis::get("posts:page:{$page}"));

        if (!$posts) {
            $posts = Blog::paginate(15);
            Blog::updatePostPaginate();
        }

        return \response()->json($posts, 200);
    }

    public function store(StorePost $request)
    {
        $data = $request->validated();
        $data['slug'] = str_slug($data['title']);

        $post = Blog::create($data);

        return response()->json($post, 200);
    }

    public function update(UpdatePost $request, Blog $blog)
    {
        $blog->update($request->validated());

        return response()->json($blog, 200);
    }

    public function destroy(Blog $blog)
    {
        return response()->json(['status' => $blog->delete()], 200);
    }

    public function show(Blog $post)
    {
        $postR = json_decode(Redis::get("posts:{$post->id}"));
        if (!$postR) {
            Blog::updateRedisInfo($post);
        }
        return \response()->json($post, 200);
    }

    public function getPostBySlug($slug)
    {
        $id = Redis::hget("posts:lookup:slug", $slug);

        if (!$id) {
            $post = Blog::where(['slug' => $slug])->first();
            if($post){
                Blog::updateRedisInfo($post);
            }else{
                return \response()->json(['error'=>'post not found'], 404);
            }
        } else {
            $post = json_decode(Redis::get("posts:{$id}"));
        }

        return \response()->json($post, 200);
    }
}
