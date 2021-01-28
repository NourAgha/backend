<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Payment;
use Illuminate\Support\Facades\DB;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all orders from DB
        $user = Auth::user();
        $orders = DB::table('orders')
            ->select('orders.*','categories.name','payments.value')
            ->join('categories', 'orders.category_id', '=', 'categories.id')
            ->leftJoin('payments', 'payments.id', '=', 'orders.payment_id')
                ->where('orders.user_id', '=', $user->id)
                ->orderBy('orders.created_at', 'desc')
                ->get();
        return response()->json($orders, 201);
    }

    public function getpendingorders(){
         $orders = DB::table('orders')
            ->select('categories.name as category_name','users.coordinate as coordinate',
                'users.id as user_id','users.name as user_name','orders.id as id',
                'orders.amount as amount','orders.sent_to as sent_to','orders.status as status',
                'users.img as user_img','users.phoneNbr as user_nbr','users.email as user_email')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('categories', 'categories.id', '=', 'orders.category_id')
            ->where('status', '<>', 'Delivered')
            ->get();
        return $orders;
    }

    public function getmypendingorders(){
        $user = Auth::user();
         $requests = DB::table('orders')
            ->select('categories.name as category_name','orders.id as id',
                'users.coordinate as coordinate',
                'users.id as user_id','users.name as user_name',
                'orders.amount as amount','orders.sent_to as sent_to','orders.status as status')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('categories', 'categories.id', '=', 'orders.category_id')
            ->where('orders.status', '<>', 'delivered')
            ->where('orders.sent_to', '=', $user->id)
            ->get();
        return response()->json(['success'=>true,'requests'=>$requests], 201);
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
        //add new order to DB
        $result ='';
        $payment_id=0;
        $user = Auth::user();
        $validator=Validator::make($request->all(),[
            'amount' => 'required',
            'user_id' => 'required',
            'value'=>'required',
            'category_id' =>'required',
            'coordinate'=>'required',
        ]);

        // app()->call('App\Http\Controllers\UserController@updateLocation',$request['coordinate']);
        // update the current position of the user who requested the order
        $affected = DB::table('users')
              ->where('id', $user->id)
              ->update(['coordinate' => $request['coordinate']]);
        $request->request->remove('coordinate');

        $input =$request->all()+['user_id' =>$user->id];
        if($request['value'] != 0){
            $payment= Payment::create(['value'=>$request['value'],
                'user_id'=>$user->id,'currency'=>'$',
                'method'=>'cash']);
            $payment_id=$payment->id;
        }
        if ($payment_id != 0){
            $input = $input + ['payment_id'=>$payment_id];
        }
        $order = Order::create($input);

        $categoryname=DB::table('categories')
                ->select('name')
                ->where('id', $request['category_id'])->get();

        if ($request['sent_to']){
            $sent_to = DB::table('users')
                    ->select('notificationToken')
                    ->where('id', $request['sent_to'])->get();
            $result=['success'=>true,'order'=>$order,'notificationToken'=>$sent_to[0],'category_name'=>$categoryname[0]];
        }
        else{
            $result =['success'=>true,'order'=>$order,'category_name'=>$categoryname[0]];
        }
        return response()->json($result,201);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateStatus(Request $request, Order $order){
        // update the order status // pending//delivering//delivered
        $order->status = $request['status'];
        $order->save();

        $notificationToken=DB::table('users')
                ->select('notificationToken')
                ->where('id', $request['user_id'])->get();
        return response()->json(['success'=>true,'notificationToken'=>$notificationToken[0]],201);
    }

    public function update(Request $request, Order $order)
    {

        $order->update($request->all());

        return response()->json($order, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            DB::table('orders')->where('id', '=', $id)->delete();
            return response()->json(['success'=>'true'], 201);

        }catch(exception $e){
            return response()->json(['error'=>$e.getMessage()], 201);
        }
    }
    
}
