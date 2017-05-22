<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait;
    use Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $fillable = [
        'name', 'alamat','no_hp','username','email','password'
    ];

    public function get_data_by_username_password($username,$password)
    {
        //query with eloquent laravel
        $user = $this->select('id as user_id','email','name','gender','alamat','no_hp','password')
            ->whereRaw("username = '".$username."'")
            ->first();
        if(\Hash::check($password,$user['password'])){
            return $user;
        }else{
            return null;
        }
    }

    public function Hash_Password($password)
    {
        return bcrypt($password);
    }

    public function Generate_JWT($data)
    {
        $key = env('JWT_SECRET','no kes');
        $date = new \DateTime;
        $token = array(
            "sub" => "access_token",
            "iat" => $date->format('U'),
            "data" => $data
        );
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }
}
