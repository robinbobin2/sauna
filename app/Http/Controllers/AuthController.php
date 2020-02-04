<?php

namespace App\Http\Controllers;

use App\Page;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InstagramAPI\Instagram;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);
        $phone = $request->phone;
        $randomNumber = rand(1000, 9999);
        $user = new User([
            'name' => $request->name,
            'surname' => $request->surname,
            'password' => bcrypt($request->password),
            'hotel_id' => 0,
            'phone' => $request->phone,
            'verified' => false,
            'code' => $randomNumber,
            'username' => $request->username,
        ]);
        $user->save();
        $url = 'https://smsc.ru/sys/send.php?login=vk_569802&psw=Givemethemoney1&phones=' . $phone . '&mes=Ваш проверочный код для сайта sauna24ufa.ru: ' . $randomNumber;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        $response = json_decode($contents, true);
        return response()->json($user);
    }
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->id);
        if ($user->code == $request->code) {
            $user->verified = true;
            $user->save();
            return response()->json([
                'message' => 'Successfully verified user!'
            ], 201);
        }
        return response()->abort();
    }
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required|string',

        ]);
        $credentials = request(['phone', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(500);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
