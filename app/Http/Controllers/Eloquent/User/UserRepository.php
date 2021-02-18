<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 12/06/2019
 * Time: 08:32 ص
 */

namespace App\Http\Controllers\Eloquent\User;


use App\Http\Controllers\Interfaces\User\UserRepositoryInterface;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use DB;

class UserRepository implements UserRepositoryInterface
{
    public function userById($id)
    {
        return User::whereId($id)->first();
    }

    public function getNotification($id)
    {
        return Notification::whereUserId($id)->whereSendTo('user');
    }

    public function getChatList($input)
    {
        return Message::whereUserId($input->user_id)->select('id','worker_id','order_id')->groupBy('order_id')->with('worker')->get();
    }

    public function updateUser($input)
    {
        $email_check = User::where('id','!=', $input->user_id)->where('email', $input->email)->first();
        if($email_check) return 'email_exist';

        $phone_check = User::where('id','!=', $input->user_id)->where('phone', $input->phone)->first();
        if($phone_check) return 'phone_exist';

        $user = User::whereId($input->user_id)->first();

        $user->name = $input->name;
        $user->city_id = $input->city_id;
        $user->lat = $input->lat;
        $user->lng = $input->lng;

        if($input->image)
        {
            $user->image = $input->image;
        }
        if($input->commercial_register)
        {
            $user->commercial_register = $input->commercial_register;
        }
        $user->save();
        return $user;
    }

    public function updatePassword($input)
    {
        $user = $this->userById($input->user_id)->first();
        /*if(1==1)*/
        if(Hash::check($input->old_password,$user->password))
        {
           DB::table("users")
           ->where('id', $input->user_id)
           ->update(['password' => Hash::make($input->new_password)]);
            return true;
        }
        else return false;
    }

}
