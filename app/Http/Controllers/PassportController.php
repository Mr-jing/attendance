<?php

namespace App\Http\Controllers;


use App\Models\User;

class PassportController extends Controller
{

    public function getLogin()
    {
        return view('passport.login');
    }


    public function postLogin()
    {
        $account = \Request::input('account');
        $jobNumber = \Request::input('job_num');

        $user = User::where('name', $account)
            ->where('job_num', $jobNumber)
            ->first();

        if (empty($user)) {
            return redirect('/login')
                ->with('message', 'Login Failed')
                ->withInput(\Request::only('account', 'job_num'));
        }

        return redirect('/record/' . date('Y/m'))
            ->withCookie(cookie('job_num', $user->job_num, time() + 3600 * 24 * 7));
    }


    public function postLogout()
    {
        return redirect('/login')
            ->withCookie(\Cookie::forget('job_num'));
    }

}
