<?php

namespace App\Controllers;

use App\Models\User;


class PassportController {


    public function getLogin() {
        require VIEW_PATH . '/login.php';
    }


    public function postLogin() {
        $user = User::where('name', $_POST['account'])
                ->where('job_num', $_POST['job_num'])
                ->first();

        if (empty($user)) {
            header('Location:login');
            exit();
        }

        setcookie('job_num', $user->job_num, time() + 3600 * 24 * 7);

        header('Location:record/' . date('Y/m'));
        exit();
    }


    public function postLogout() {
        setcookie('job_num', null, time() - 3600);
        header('Location:login');
        exit();
    }

}
