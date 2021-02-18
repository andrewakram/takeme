<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 28/07/2019
 * Time: 02:43 م
 */

namespace App\Http\Controllers\Interfaces\Admin;


interface AdminRepositoryInterface
{
    public function index();
    public function show($id);
    public function add($attributes);
    public function update($attributes,$id);
}
