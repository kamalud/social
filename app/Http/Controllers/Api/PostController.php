<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = 10;
        if(isset($request->limit)) $limit = $request->limit;

        $post  = Post::all()->orderBy('id','DESC')->paginate($limit);

        return response()->json([$post]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'media_type'=>'required|string|in:image,video',
            'media_thumbnail'=>'required_if:media_type,video',
            'media_link'=>'required',
            'visiblity'=>'required|in:public,followers',
        ]);
        $post  = new Post();
        if($request->hasFile('media_link')){
        if($request->media_type == 'image'){
           $request->validate([
                'media_link'=>'image|mimes:jpg,jpeg,png|max:5120',
            ]);
        }else{
           $request->validate([
                'media_link'=>'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:5120'
            ]);
            if($request->hasFile('media_thumbnail')){
               $request->validate([
                    'media_thumbnail'=>'image|mimes:jpg,jpeg,png|max:5120',
                ]);
                $post->media_thumbnail = $request->file('media_thumbnail')->store('media_link');
            }

            
        }
        $post->media_link = $request->file('media_link')->store('media_link');
    }

        $post->user_id = $request->user()->id;
        $post->media_type = $request->media_type;
        $post->visiblity = $request->visiblity;
        $post->body = $request->body;
        
        if($post->save()){
          return response()->json([$post,'message'=>'post add successfuly']);
        }else{
            return response()->json(['message'=>'Some error occurred']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
