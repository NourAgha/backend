<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use Validator;

class UserController extends Controller
{

      public function index()
    {
        // get all users from DB
        $Users = User::latest()->get();

        return response()->json($user, 201);
    }

	private $successStatus =200;
    public function login(){
        // handle the login process and generate token 
    	if(Auth::attempt(['email'=>request('email'),'password'=>request('password')])){
    		$user=Auth::user();
    		$success['token'] = $user->createToken('MyApp')->accessToken;

             $generate_token=app()->call('App\Http\Controllers\ChatMessagesController@generateToken');
            $success['user_id'] = $user->id;
            $success['user_name']=$user->name;
            $success['user_type']=$user->userType;
            $success['notificationToken']=$user->notificationToken;
            $success['phoneNbr']=$user->phoneNbr;
            $success['img']=$user->img;
            $success['stream_token']=$generate_token;
            $success['api_key']=env('MIX_STREAM_API_KEY');
            
    		return response()->json(['success'=>$success],$this->successStatus);
    	}
    	else{
    		return response()->json(['error'=>'Unauthorized'],201);
    	}
    }

    public function register(Request $request){
        //register a new user and create token 
    	$validator=Validator::make($request->all(),[
    		'name'=>'required',
    		'email'=>'required',
    		'password'=>'required',
    		'c_password'=>'required|same:password',
    		'userType' => 'required',
            'notificationToken'=>'required',
            'phoneNbr'=>'required',
    	]);
    	if($validator->fails()){
    		return response()->json(['error'=>'validator error'],401);
    	}
    	$input =$request->all();
		$input['password']= bcrypt($input['password']);
		$user = User::create($input);

		$success['token'] = $user->createToken('MyApp')->accessToken;

        $generate_token=app()->call('App\Http\Controllers\ChatMessagesController@generateToken');

        $success['user_id'] = $user->id;
        $success['user_name']=$user->name;
        $success['user_type']=$user->userType;
        $success['notification_token']=$user->notificationToken;
        $success['phoneNbr']=$user->phoneNbr;
        $success['stream_token']=$generate_token;
        $success['api_key']=env('MIX_STREAM_API_KEY');

    	return response()->json(['success'=>$success],$this->successStatus);
    }

    public function logout(){
        $user = Auth::user();
        if($user instanceOf User)
            $logout = $user->token()->revoke();
        return response()->json([
            'information' => 'you are logout'
        ], 201);
    }
    public function getdeliveringstations(){
        $stations = DB::table('users')
            ->select('users.userType','users.coordinate','users.name','users.id','users.img','users.phoneNbr','users.email')
            ->join('user_types', 'user_types.id', '=', 'users.userType')
            ->where('user_types.desc', '=', 'delivering_station')
            ->get();
        $orders=app()->call('App\Http\Controllers\OrderController@getpendingorders');
        return response()->json(["success"=>true,"stations"=>$stations,"orders"=>$orders], 201);
    }

    public function updateLocation($location){
        $user = Auth::user();
        $affected = DB::table('users')
              ->where('id', $user->id)
              ->update(['coordinate' => $location]);
        return response()->json(["success"=>true], 201);
    }

    public function update(Request $request){
        $user = Auth::user();
        $affected = DB::table('users')
              ->where('id',$user->id)
              ->update(['phoneNbr' => $request['phoneNbr'],'name'=> $request['name'],
                'img'=> $request['img']]);
        return response()->json(['success'=>true],201);
    }
}
