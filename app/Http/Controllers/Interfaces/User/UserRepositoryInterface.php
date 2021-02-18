<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 12/06/2019
 * Time: 08:32 ص
 */

namespace App\Http\Controllers\Interfaces\User;


interface UserRepositoryInterface
{
    public function userById($id);
    public function getNotification($id);
    public function getChatList($attributes);
    public function updateUser($attributes);
    public function updatePassword($attributes);
}
