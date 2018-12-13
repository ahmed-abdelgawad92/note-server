<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
          'users' => $users
        ],200);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function signUp(Request $req){
      $rules=[
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email|max:255',
        'passwordGroup.password' => 'required|min:8',
        'passwordGroup.repassword' => 'same:passwordGroup.password',
        'gender' => 'in:male,female'
      ];
      $error_messages=[
        'passwordGroup.password.required' =>'Password is required',
        'passwordGroup.password.min' =>'Password must be minimum 8 characters',
        'passwordGroup.repassword.same' =>'Passwords don\'t match'
      ];
      $validator = Validator::make($req->all(),$rules,$error_messages);
      if ($validator->fails()) {
       	return response()->json($validator->messages(), 200);
      }
      $user = new User;
      $user->fill([
        'fname' => $req->input('fname'),
        'lname' => $req->input('lname'),
        'email' => $req->input("email"),
        'password' => $req->input('passwordGroup.password'),
        'gender' => $req->input('gender'),
      ]);
      $saved = $user->save();
      //check if saved correctly
      if (!$saved) {
        return response()->json([
          'error' => 'Something went wrong during signing up , please try again later'
        ],500);
      }
      return response()->json([
        'success' => "You have successfully signed up!"
      ],201);
    }
    /**
     *
     * authenticate the results
     *
     *
     */
    protected function signIn(Request $req){
      $rules=[
        'email' => 'required|email|max:255',
        'password' => 'required'
      ];
      $validator = Validator::make($req->all(),$rules);
      if ($validator->fails()) {
       	return response()->json($validator->messages(), 200);
      }
      $credentials = $req->only('email', 'password');
      try{
        if(!$token = JWTAuth::attempt($credentials)){
          return response()->json([
            'error' => 'Invalid Credentials!'
          ], 401);
        }
      } catch (JWTException $e){
        return response()->json([
          'error' => 'Couldn\'t create a token!'
        ], 500);
      }
      return response()->json([
        'token' => $token
      ], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function token(){
         // $token = JWTAuth::getToken();
         // if(!$token){
         //     throw new BadRequestHtttpException('Token not provided');
         // }
         // try{
         //     $token = JWTAuth::refresh($token);
         // }catch(TokenInvalidException $e){
         //     throw new AccessDeniedHttpException('The token is invalid');
         // }
         // return response()->json(['token'=>$token]);
        $token = JWTAuth::getToken();

        try
        {
            $newToken = JWTAuth::refresh($token);
        }
        catch (TokenExpiredException $e)
        {
            return response()->json([
                'success' => false,
                'data' => [
                    'errors' => [
                        'auth_token' => [
                            'token_expired'
                        ]
                    ]
                ],
            ], $e->getStatusCode());
        }
        catch (TokenInvalidException $e)
        {
            return response()->json([
                'success' => false,
                'data' => [
                    'errors' => [
                        'auth_token' => [
                            'token_invalid'
                        ]
                    ]
                ],
            ], $e->getStatusCode());
        }
        catch (JWTException $e)
        {
            return response()->json([
                'success' => false,
                'data' => [
                    'errors' => [
                        'auth_token' => [
                            'token_absent'
                        ]
                    ]
                ],
            ], $e->getStatusCode());
        }

        return response()->json([
            'success' => true,
            'token' => $newToken
        ], 200);
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
        //
    }
}
