<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id'=>'required',
        ]);
        $like = Like::where('user_id',$request->user()->id)->where('post_id',$request->post_id)->first();

         if($like){
           $like->delete();
           return response()->json(['message'=>'You Unlike a post'],200);
         }else{
          $like  = new Like();
          $like->user_id = $request->user()->id;
          $like->post_id = $request->post_id;
          
          if($like->save()){
            return response()->json([
                'message'=>'You like a post',
                'comment'=>$like->load('user')
            ],200);
        }else{
            return response()->json([
                'message'=>'commentsome error occurred',
            ],500);
        }
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
