<?php

namespace App\Models;


class UserCreator
{


    public static function create($data)
    {
        $user = User::where('job_num', $data['job_num'])->first();

        if (!empty($user)) {
            return false;
        }

        $user = new User();
        $user->job_num = $data['job_num'];
        $user->name = $data['name'];

        return $user->save();
    }

}
