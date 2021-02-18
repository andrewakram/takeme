<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/15/2019
 * Time: 10:55 PM
 */

namespace App\Http\Controllers\Interfaces\Admin;


interface HomeRepositoryInterface
{
    public function dashboard();
    public function settings($type);
    public function updateSettings($type,$attributes);
}
