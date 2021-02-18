<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/8/2019
 * Time: 12:28 AM
 */

namespace App\Http\Controllers\Interfaces\Admin;


interface UserRepositoryInterface
{
    public function activeUsers();
    public function suspendedUsers();
    public function createUser($attributes);
    public function profile($user_id);
    public function changStatus($user_id);
    public function search($attributes);
}
