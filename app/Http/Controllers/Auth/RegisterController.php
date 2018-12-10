<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'gender' => $data['gender']==='male'? 1 : 0
        ]);
    }
    function signUp(Request $req){
      $rules=[
        'fname' => 'required',
        'lname' => 'required',
        'email' => 'required|email|unique:users,email',
        'passwordGroup.password' => 'required|min:8',
        'passwordGroup.repassword' => 'same:passwordGroup.password',
        'gender' => 'in:male,female'
      ];
      $validator = Validator::make($req->all(),$rules);
       if ($validator->fails()) {
       	return response()->json($validator->messages(), 200);
      }
      return User::create([
        'fname' => $req->fname,
        'lname' => $req->lname,
        'email' => $req->email,
        'password' => Hash::make($req->password),
        'gender' => $req->gender==='male'? 1 : 0
      ]);
    }
}
