<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User, JWTAuth, DB, Validator, Carbon\Carbon, Auth;

class UserController extends Controller
{
    public $tableVerification = 'account_verification';
    public $tableResetPwd = 'password_resets';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) return $this->error($validator->errors()->first());

        $defaultUrlAvatar = urlencode(env('DEFAULT_URL_AVATAR'));

        try {
            $user = User::create([
                'uuid' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10),
                'name' => $request->name,
                'email' => $request->email,
                'avatar' => "https://www.gravatar.com/avatar/" . md5(strtolower(trim($request->email))) . "?d=" . $defaultUrlAvatar . "&s=" . 460,
                'password' => \Hash::make($request->password)
            ]);

            $verificationCode = \Str::random(60);

            DB::table($this->tableVerification)->insert([
                'user_id' => $user->id, 'hash' => $verificationCode
            ]);
            $this->sendEmail('email.verify', [
                'name' => $request->name,
                'hash' => $verificationCode
            ], [
                'email' => $request->email,
                'name' => $request->name
            ], __('user.verify_your_account'));

            return $this->success(__('user.email_verification_sended'));
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function verify($code)
    {
        $verification = DB::table($this->tableVerification)->whereHash($code)->first();
        if ($verification) {
            $user = User::find($verification->user_id);
            if (!$user->email_verified_at) {
                $user->email_verified_at = Carbon::now();
                $user->save();
                DB::table($this->tableVerification)->whereHash($code)->delete();
                // send welcome email
                return $this->success(__('user.account_successfully_activated'));
            }
        }
        return $this->error(__('user.invalid_link_confirmation'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = JWTAuth::attempt($credentials)) {
            return $this->successData(['token' => $token]);
        }
        return $this->error(__('user.login_fail'));
    }

    public function requestPasswordReset(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
        if ($validator->fails()) return $this->error($validator->errors()->first());

        $user = User::whereEmail($request->email)->first();
        if ($user) {
            $token = \Str::random(45);
            DB::table($this->tableResetPwd)->updateOrInsert(
                ['user_id' => $user->id],
                ['token' => $token]
            );

            $this->sendEmail('email.forgot', [
                'name' => $user->name,
                'token' => $token
            ], [
                'email' => $user->email,
                'name' => $user->name
            ], __('user.email_subject_forgot'));
        }

        return $this->success(__('user.sended_email_forgot_pwd'));
    }


    public function resetPwd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) return $this->error($validator->errors()->first());

        $token = $request->token;
        $reset = DB::table($this->tableResetPwd)->whereToken($token)->first();

        if (!$reset) return $this->error(__('user.token_reset_pwd_invalid'));

        $tokenCreated = Carbon::parse($reset->created_at);
        $createdAgo = Carbon::now()->diffInSeconds($tokenCreated);

        $expiredToken = $createdAgo > env('SECONDS_TO_EXPIRY_TOKEN_RESET_PWD', 7200);

        if ($expiredToken) return $this->error(__('user.token_reset_pwd_invalid'));

        $user = User::find($reset->user_id);
        $user->password = \Hash::make($request->password);
        $user->save();

        DB::table($this->tableResetPwd)->whereToken($token)->delete();

        $this->sendEmail('email.confirm-reset-pwd', [
            'name' => $user->name
        ], [
            'email' => $user->email,
            'name' => $user->name
        ], __('user.email_subject_confirm_reset_pwd'));

        return $this->success(__('user.reseted_pwd'));
    }

    public function my() {
        return $this->successData(Auth::user());
    }
}
