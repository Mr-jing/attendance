<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class User extends Eloquent {

    protected $table = 'users';
    public $timestamps = false;
    protected $primaryKey = 'job_num';


    public function records() {
        return $this->hasMany('App\Models\Record', 'job_num');
    }

}
