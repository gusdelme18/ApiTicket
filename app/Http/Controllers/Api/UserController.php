<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\UserResource;

   
class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
   
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
            ];
    
            if(!empty($validator->errors())){
                $response['data'] = $validator->errors();
            }
    
            return response()->json($response, 200);   
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        $response = [
            'success' => true,
            'data'    => $success,
            'message' => 'User register successfully.',
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['user'] =  $user;
   
            $response = [
                'success' => true,
                'data'    => $success,
                'message' => 'User login successfully.',
            ];
    
            return response()->json($response, 200);
        } 
        else{ 
            $response = [
                'success' => false,
                'message' => 'Unauthorised',
            ];
    
            if(!empty($validator->errors())){
                $response['data'] = ['error'=>'Unauthorised'];
            }
    
            return response()->json($response, 404);  

        } 
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::all();    
        $response = [
            'success' => true,
            'data'    => UserResource::collection($users),
            'message' => 'Users retrieved successfully.',
        ];

        return response()->json($response, 200);
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
   
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
            ];
    
            if(!empty($validator->errors())){
                $response['data'] = $validator->errors();
            }
    
            return response()->json($response, 404);       
        }

        $user = User::create($input);

        $response = [
            'success' => true,
            'data'    => new UserResource($user),
            'message' => 'User created successfully.',
        ];

        return response()->json($response, 200);
   
   
   
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, $id)
    {
        //
        $user = User::find($id);
  
        if (is_null($user)) {
            $response = [
                'success' => false,
                'message' => 'User not found.',
            ];
    
            return response()->json($response, 404);  
        }

        $response = [
            'success' => true,
            'data'    => new UserResource($user),
            'message' => 'User retrieved successfully.',
        ];

        return response()->json($response, 200);    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required'
        ]);
   
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
            ];
    
            if(!empty($validator->errors())){
                $response['data'] = $validator->errors();
            }
    
            return response()->json($response, 200);   
        }
   
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->save();

        $response = [
            'success' => true,
            'data'    => new UserResource($user),
            'message' => 'User updated successfully.',
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
   
        $response = [
            'success' => true,
            'data'    => '',
            'message' => 'User deleted successfully.'
        ];

        return response()->json($response, 200);
    }
}
