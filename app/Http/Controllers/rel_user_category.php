<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class rel_user_category extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all the categories that are related to one station
        $user = Auth::user();
        $categories = DB::table('rel_user_categories')
       ->join('categories', 'rel_user_categories.category_id', '=', 'categories.id')
       ->join('users', 'users.id', '=', 'rel_user_categories.user_id')
       ->select('rel_user_categories.id', 'categories.id as category_id','categories.name','categories.costperliter','categories.currency','rel_user_categories.status')
       ->where('rel_user_categories.user_id', '=', $user->id)
        ->orderBy('rel_user_categories.created_at', 'desc')
        ->get();
        return response()->json($categories, 201);
    }

    public function get($id)
    {
        $categories = DB::table('rel_user_categories')
       ->join('categories', 'rel_user_categories.category_id', '=', 'categories.id')
       ->join('users', 'users.id', '=', 'rel_user_categories.user_id')
       ->select('categories.id','categories.name','categories.costperliter','categories.currency','rel_user_categories.status','rel_user_categories.cost')
       ->where('rel_user_categories.user_id', '=', $id)
        ->orderBy('rel_user_categories.created_at', 'desc')
        ->get();
        return response()->json($categories, 201);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add category of a station
        $user = Auth::user();
        $validator=Validator::make($request->all(),[
            'category_id' => 'required',
        ]);
        $request['user_id']=$user->id;
        $input =$request->all();
        $cat = rel_user_category::create($input);
        return response()->json(['success'=>true,'id'=>$cat->id ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $affected = DB::table('rel_user_categories')
              ->where('id', $id)
              ->update(['status' => $request['status'],'cost'=> $request['cost']]);
        return response()->json(['success'=> true], 201);
    }

    public function updateStatus(Request $request){

        // update the category status // available//unavailable
        DB::update('update rel_user_categories set status = ? where id = ?',[$request['status'],$request['id']]);

        return response()->json(['success'=>true],201);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete a category
        DB::table('rel_user_categories')->where('id', '=', $id)->delete();
        return response()->json(['success'=>true],201);

    }
}
