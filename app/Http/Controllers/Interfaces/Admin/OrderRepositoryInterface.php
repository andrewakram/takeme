<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 09/07/2019
 * Time: 10:53 ص
 */

namespace App\Http\Controllers\Interfaces\Admin;


interface OrderRepositoryInterface
{
    public function index();
    public function indexCost();
    public function view($id);
    public function acceptOrder($id);
    public function rejectOrder($id);
    public function finishOrder($id);
    public function search($attributes);
    public function search2($attributes);
    public function export();
}
