<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\Events\Registered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'username' => 'required|max:60',
            'nama' => 'required',
            'telp' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }
        $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);
        event(new Registered($user));
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        if(!Auth::attempt($loginData))
        {
            return response(['message' => 'Invalid Credentials'], 401); 
        }

        $user = Auth::user();
        if ($user->email_verified_at == NULL) {
            return response([
                'message' => 'Please Verify Your Email'
            ], 401);
        }
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if(is_null($user))
        {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        
        if($user->delete())
        {
            return response([
                'message' => 'Delete User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Delete User Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if(is_null($user))
        {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'username' => 'required|max:60',
            'nama' => 'required',
            'telp' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $updateData['password'] = bcrypt($request->password);
        $user->username = $updateData['username'];
        $user->nama = $updateData['nama'];
        $user->telp = $updateData['telp'];
        $user->password = $updateData['password'];

        if($user->save())
        {
            return response([
                'message' => 'Update User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Update User Failed',
            'data' => null
        ], 400);
    }

    public function index()
    {
        $users = User::all();

        if(count($users) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $users
            ], 200);
        }
        else
        {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
    }

    public function show($id)
    {
        $user = User::where('id', $id)->get();

        if(!is_null($user))
        {
            return response([
                'message' => 'Retrieve User Success',
                'username' => $user[0]->username,
                'nama' => $user[0]->nama,
                'telp' => $user[0]->telp,
                'email' => $user[0]->email,
            ], 200);
            //ada cara lain untuk passing data
        }
        else
        {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
    }
}
