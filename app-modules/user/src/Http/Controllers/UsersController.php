<?php

namespace Modules\User\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if($request->has('verified')){
                $users = User::where('verified',$request->verified)->get()->load(['money_received','money_sent']);
            }
                $users = User::withTrashed()->get()->load(['money_received','money_sent']);
            if(!$users){ 
                return $this->formatAsJson(false,'No users created','','',404); 
            }
            return $this->formatAsJson(true,'List of all users',$users,'',200);
        } catch (Exception $e) {
            return $this->formatAsJson(true,'An error occurred', $e->getMessage(),'',500);
        }
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
        $data = $request->except(['account_balance','pin']);
        $rules = [
            'last_name' => 'required|min:3',
            'first_name' => 'required|min:3',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users|min:8',
        ];
        $validator = \Validator::make($data, $rules);

        if ($validator->fails()) {
            return $this->formatAsJson(false,'Validation didnt pass',[],$validator->errors(),422);
        }
        try {
            $newUser = User::create($request->all());
            if($newUser){
                $user = User::latest()->first();
                return response()->json([
                    'status'=>true,
                    'message'=>'User was created successfully',
                    'data'=>$user,
                    'SPIN' => $user->wallet_id,
                    'secretpin' => $user->pin
                ],201);
            }
        } catch (Exception $e) {
            return $this->formatAsJson(false,'User failed to create', [],$e->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        try {
            if($request->has('balance')){
                $userData = User::find((int) $id)->account_balance;              
            }else{
                  $userData = User::where('id',$id)->with(['money_received','money_sent'])->first();   
            }
             
            $receivedCount = count($userData['money_received']);
            $sentCount = count($userData['money_sent']);
            for ($i=0; $i < $receivedCount; $i++) { 
                $senderID = $userData['money_received'][$i]['sender_id'];
                $userData['money_received'][$i]['sent_from'] = $this->getName((int)$senderID);
            }
            for ($i=0; $i < $sentCount; $i++) { 
                $receiverID = $userData['money_sent'][$i]['receiver_id'];
                $userData['money_sent'][$i]['sent_to'] = $this->getName((int)$receiverID);
            }

            if(!$userData || $userData == null){ return $this->formatAsJson(true,'User not found','','',404); }
            return $this->formatAsJson(true,'User info',$userData,'',200);
        } catch (Exception $e) {
            return $this->formatAsJson(false,'An error occurred', $e->getMessage(),'',500);
        }
    }

    public function getName($id)
    {
        $user = User::withTrashed()->where('id',$id)->first();
        if($user){
            return $full_name = $user->first_name .' '.$user->last_name;
        }else{
            return $full_name = '';
        }
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
        try {
            $user = User::where('id', $id)->update($request->all());
            if ($user == true) {
                return response()->json(['status' => true, 'message' => 'Update Successful'], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Failed to Update'], 401);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function formatAsJson($status, $message='',$data=[],$meta='',$status_code=''){
        return response()->json([
            'status'=> $status,
            'message'=> $message,
            'data'=> $data,
            'meta'=>$meta
        ],$status_code);
    }

    public function login(Request $request)
    {
        $user = User::where('email',$request->email)->get();
        
        if ($this->attemptLogin($user[0], $request)) {   //Attempt to log in user/user
            $accessToken = $user->createToken('authToken',['server:update'])->plainTextToken;
            $user_data = [
                "data" => $data=$user,
                "access_token" => $accessToken,
                "token_type" => "Bearer"
            ];
            return "Login successful";
        }
        return "failed to login check password";
    }

    public function attemptLogin(Object $user, Object  $request)
    {
        return (Hash::check($request->password, $user->password)) ? true : false;
    }
    
}
