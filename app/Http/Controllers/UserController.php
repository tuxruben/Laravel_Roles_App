<?php

namespace App\Http\Controllers;

use App\Project;
use App\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name'     => $request->get('name'),
            'email'    => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        $user->roles()->attach(Role::where('name', 'cliente')->first());
        return response()->json(compact('user', 'token'), 201);
    }
    protected function redirectTo()
    {
        if (Auth::user()->hasRole('cliente')) {
            return "/cliente/home";
        }
        return "/";

    }
    public function redirectPath()
    {
        if (Auth::user()->hasRole('admin')) {
            return "/admin/usuarios";
        }
        if (Auth::user()->hasRole('cliente')) {
            return "/cleinte/home";
        }
        return "/";

    }
    public function token()
    {

        $user = auth()->user();

        return $user;
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'User successfully signed out']);
    }
    public function userIndex(Request $request)
    {

        $res = User::where("id", "like", '%' . $request->search . '%')
            ->orWhere("name", "like", '%' . $request->search . '%')
            ->orWhere("email", "like", '%' . $request->search . '%')
            ->orderBy("name")
            ->paginate(2);
        $res->appends("search", $request->search)->links();
        //event(new Peticion("Consulto los registros de la tabla usuarios"));
        //return View::make('users')->with('users', $res);
        return $res;
    }
    public function x(Request $request)
    {

        Project::create([
            'title'       => request('title'),
            'description' => request('description'),
            'url'         => request('url'),
        ]);
        return $request->all();
    }

}
