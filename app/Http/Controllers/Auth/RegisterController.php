<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
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
* @param array $data
* @return \Illuminate\Contracts\Validation\Validator
*/
protected function validator(array $data)
{
return Validator::make($data, [
'name' => ['required', 'string', 'max:255'],
'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
'password' => ['required', 'string', 'min:8', 'confirmed'],
'address' => ['required', 'string','max:255'],
'long' => ['nullable', 'numeric', 'between:-180,180'],
'lat' => ['nullable', 'numeric', 'between:-90,90'],

]);
}

/**
* Create a new user instance after a valid registration.
*
* @param array $data
* @return \App\Models\User
*/
protected function create(array $data)
{
return User::create([
'name' => $data['name'],
'email' => $data['email'],
'password' => Hash::make($data['password']),
'address' => $data['address'],
'long' => $data['long'] ?? 0,
'lat' => $data['lat'] ?? 0,
]);
}
}