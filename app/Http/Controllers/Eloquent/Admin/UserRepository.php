<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/8/2019
 * Time: 12:28 AM
 */

namespace App\Http\Controllers\Eloquent\Admin;


use App\Http\Controllers\Interfaces\Admin\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    public function activeUsers()
    {
        return User::where('active', 1);
    }

    public function suspendedUsers()
    {
        return User::where('active', 0);
    }

    public function createUser($input)
    {
        $array = array
        (
            'jwt' => Str::random(25),
            'role' => $input->role,
            'name' => $input->name,
            'email' => $input->email,
            'phone' => $input->phone,
            'password' => Hash::make($input->password),
            'lat' => $input->lat,
            'lng' => $input->lng,
            'active'=>1,
        );

        $email = User::whereEmail($input->email)->select('id')->first();
        if($email)
            return 'email_exist';

        $phone = User::wherePhone($input->phone)->select('id')->first();
        if($phone)
            return 'phone_exist';

        if($user = User::create($array))
        {
            if($input->image)
            {
                $user->image = $input->image;
            }

            if(isset($input->commercial_register))
            {
                $user->commercial_register = $input->commercial_register;
            }
            $user->save();
        }
        return $user;
    }

    public function profile($user_id)
    {
        return User::whereId($user_id)->first();
    }

    public function changStatus($user_id)
    {
        $user = User::whereId($user_id)->select('id','active')->first();

        if($user->active == 1)
        {
            $user->update(['active'=>0]);
            return 'suspend';
        }
        else{
            $user->update(['active'=>1]);
            return 'active';
        }
    }

    public function search($input)
    {
        $search = Input::get('search');

        return User::where(function($q) use($search)
        {
            $q->where('name','like','%'.$search.'%');
        }
        )->get();
    }
}
