<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Hash;

class AuthController extends Controller
{
    public function index () {
        if(Session::has('uid')) {
            return view('app.dashboard.index');
        } else {
            return view('app.auth.index');
        }
    }

    public function register (Request $request) {

        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required|min:8',
        ]);

        $user = DB::table('users')->where('username', $request->username)->first();

        if($user) {
            return response()->json(['msg' => "Account already exists with same username"]);
        }

        $hashedPassword = Hash::make($request->password);

        $data = [
            'username' => $request->username,
            'email' => !empty($request->email) ? $request->email : NULL,
            'password' => $hashedPassword,
        ];

        $is_created = DB::table('users')->insert($data);

        return response()->json(['msg' => $is_created ? "Success" : "Error"]);
    }

    public function login (Request $request) {
        $user = DB::table('users')->where('username', $request->username)->first();

        if($user) {
            if (Hash::check($request->password, $user->password)) {
                Session::put('uid', $user->id);
                session()->save();

                DB::table('users')->where('username', $request->username)->update(['last_login_at' => now()]);

                return response()->json(['msg' => "Success"]);
            } else {
                return response()->json(['msg' => "Invalid Password"]);
            }
        } else {
            return response()->json(['msg' => "Invalid Username"]);
        }
    }

    public function flushSession()
    {
        Session::flush();
        return redirect()->to('/');
    }
}
