<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User, JWTAuth, DB, Validator;

class UserController extends Controller
{
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
        $tableVerification = 'account_verification';
        $validator = $this->validator($request->all());
        if ($validator->fails()) return $this->error($validator->errors()->first());

        try {
            $user = User::create([
                'uuid' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10),
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password)
            ]);

            $verificationCode = \Str::random(60);

            DB::table($tableVerification)->insert([
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
}
