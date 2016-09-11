<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Request;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $user->api_token = $user->id.str_random(60);

        $user->save();

        return $user;
    }

    public function login()
    {
        $login = Auth::attempt(Input::all());
        if ($login) {
            // Authentication passed...
            return Response::json([
                'status' => 'success'
            ]);
        }
        return Response::json([
            'status' => 'error'
        ], 401);
    }

    public function register() {
        $data = Input::all();
        $validate = $this->validator($data);

        if ($validate->fails()) {
            return Response::json([
                'status' => 'error',
                'errors' => $validate->errors()->all()
            ], 401);
            //return ['status' => 'error'];
        }

        $this->create($data);

        return ['status' => 'success'];
    }

    public function status() {
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();

        if($user) {
            $user->feeds = User::find($user->id)->feeds()->get();

            return ['user' => $user];
        }
        return ['user' => false];
    }

    /**
     * Used to get api token with username and password
     *
     * @return mixed
     */
    public function apiLogin() {
        $req = Input::all();
        if (Auth::once($req)) {
            $user = User::where('email', '=', $req['email'])->first();
            return ['api_token' => $user->api_token];
        }
        return Response::json([
            'error' => 'Invalid Credentials'
        ], 401);
    }
}
