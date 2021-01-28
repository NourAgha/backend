<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class Category extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all categories from DB
        $categories=categories::all();
        return response()->json(['categories'=> $categories], 201);
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
        // add new category to DB
        try{
            $category_id = $request['category_id'];
            $user = Auth::user();

            $request->validate([
                'category_id'=>'required',
                'cost' => 'required',
                'currency' => 'required',
                'status'=>'required',
            ]);
            if ($request['category_id'] == 0 ){
                $category = categories::create($request->all());
                $category_id= $category->category_id;
            }
            DB::table('rel_user_categories')->insert([
                ['category_id' => $category_id, 'user_id' => $user->id,'status'=>$request['status'],'cost'=>$request['cost']],
            ]);
            return response()->json(['success'=>true,'category_id'=> $category_id], 200);
        }catch(exception $e){
            return response()->json($e->getMessage(), 201);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //remove category from DB
        try{
            $user = Auth::user();
            DB::table('categories')->where('categories_id', '=', $id)
            ->Where('user_id', '=', $user->id)->delete();
            // return response()->json('success'=>'true', 201);

        }catch(exception $e){
            // return response()->json('error'=>$e.getMessage(), 201);
        }
    }

     public function getAvailableCategories()
    {
        $user = Auth::user();
        $categories = DB::table('rel_user_categories')
       ->join('categories', 'rel_user_categories.category_id', '=', 'categories.id')
       ->select('categories.id','categories.name','categories.costperliter','categories.currency')
       ->where('rel_user_categories.user_id', '<>',$user->id)
        ->orderBy('rel_user_categories.created_at', 'desc')
        ->get();

        return response()->json(['categories'=> $categories], 201);
    }
}
