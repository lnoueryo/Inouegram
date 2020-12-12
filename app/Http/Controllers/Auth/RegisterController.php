<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Events\UserActivationEmail;
use App\Jobs\SendMail;
use Illuminate\Support\Facades\Auth;
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
            'name' => ['required', 'string', 'max:100'],
            'screen_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        if(isset($data['profile_image'])){
            $base64_image = $data['profile_image'];
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $imageName = str_random(10).'.'.'png';
            Storage::disk('avatar')->put($imageName, base64_decode($file_data));
            return User::create([
                'name' => $data['name'],
                'screen_name' => $data['screen_name'],
                'email' => $data['email'],
                'profile_image' => $imageName,
                'password' => $data['password'],
                // 'password' => Hash::make($data['password']),
            ]);
        } else {
            return User::create([
                'name' => $data['name'],
                'screen_name' => $data['screen_name'],
                'email' => $data['email'],
                'password' => $data['password'],
                // 'password' => Hash::make($data['password']),
            ]);
        }
    }
    protected function registered(Request $request, $user)
    {
        event(new UserActivationEmail($user));
        SendMail::dispatch($user);
        return Auth::login($user);
    }
}
