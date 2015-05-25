<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class PassportController extends Controller
{


    public function getLogin()
    {
        return view('passport.login');
    }


    public function postLogin()
    {
        $account = Request::input('account');
        $jobNumber = Request::input('job_num');

        $user = User::where('name', $account)
            ->where('job_num', $jobNumber)
            ->first();

        if (empty($user)) {
            return redirect('/login')
                ->with('message', 'Login Failed')
                ->withInput(Request::only('account', 'job_num'));
        }

        return redirect('/record')
            ->withCookie(cookie('job_num', $user->job_num, time() + 3600 * 24 * 7));
    }


    public function postLogout()
    {
        return redirect('/login')
            ->withCookie(Cookie::forget('job_num'));
    }

}
