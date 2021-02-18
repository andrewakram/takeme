<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/13/2019
 * Time: 8:08 PM
 */

namespace App\Http\Controllers\Interfaces\Admin;


interface CategoryRepositoryInterface
{
    public function index();
    public function getSubCat($id);
    public function getThirdCat($id);
    public function storeSub($attributes);
    public function storeThird($attributes);
    public function editCat($attributes);
    
    public function editMainCat($attributes);
}
