<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use Illuminate\Http\Request;

class FollowersController extends Controller
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
            'user_id'=>'required',
        ]);
        $user  = $request->user();
        $follower = Follower::where('user_id',$request->user_id)->where('follwing_id',$user->id)->first();

        if(!$follower){
            $follower = new Follower();
            $follower->user_id = $request->user_id;
            $follower->follwing_id = $user->id;
           if($follower->save()){
             return response()->json(['message'=>'Follwing suceess'],200);
           }else{
            return response()->json(['message'=>'some error'],500);
           }
        }else{
            if($follower->delete()){
                return response()->json(['message'=>'UnFollwing suceess'],200);
              }else{
               return response()->json(['message'=>'some error'],500);
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
