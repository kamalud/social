<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
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
            'content'=>'required|string|max:255'
        ]);

        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user()->id;
        $comment->content = $request->content;
        
        if($comment->save()){
            return response()->json([
                'message'=>'comment add successfuly',
                'comment'=>$comment->load('user')
            ],200);
        }else{
            return response()->json([
                'message'=>'commentsome error occurred',
            ],500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::find($id);
        return response()->json($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $comment = Comment::find($id);
        $comment->content = $request->content;
        $comment->update();
        return $comment;
        if($comment->save()){
            return response()->json([
                'message'=>'comment update successfuly',
                'comment'=>$comment->load('user')
            ],200);
        }else{
            return response()->json([
                'message'=>'commentsome error occurred',
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        return response()->json(['message'=>'comment delete successfuly'],200);
    }
}
