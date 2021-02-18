<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 14/07/2019
 * Time: 11:53 ص
 */

namespace App\Http\Controllers\Eloquent\Admin;


use App\Http\Controllers\Interfaces\Admin\WorkerRepositoryInterface;
use App\Models\AdminWorkerPdf;
use App\Models\Category;
use App\Models\City;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use DB;

class WorkerRepository implements WorkerRepositoryInterface
{
    public function workerApp($type)
    {
        if($type == 'active')
        {
            $type = 1;
            return Worker::where('provider_id',null)->where('active',$type)->get();
        }else{
            $type = 0;
            return Worker::where('provider_id',null)->where('active',$type)->get();
        }
    }

    public function workerCompany()
    {
        $categories = Category::where('type',1)->where('parent_id',null)->get();
        $cities = City::select('id','en_name')->get();
        $workers = Worker::where('provider_id',1)->get();
        return ['categories'=>$categories,'cities'=>$cities,'workers'=>$workers];
    }

    public function getSubCat($input)
    {
        $workers=DB::table("workers")->where("id",$input->worker_id)->first();
        if($workers->cat_id == ""){
            return Category::where('type',2)
                ->where('parent_id',$input->parent_id)->get();
        }else{
            $selectedCats=explode(",",$workers->cat_id);
            return Category::where('type',2)
                ->where('parent_id',$input->parent_id)
                ->whereNotIn("id",$selectedCats)->get();
        }


    }

    public function storeWorkerCompany($input)
    {
        $email = Worker::whereEmail($input->email)->select('id')->first();
        if($email)
            return 'email_exist';
        $phone = Worker::wherePhone($input->phone)->select('id')->first();
        if($phone)
            return 'phone_exist';

        Worker::create([
            'role' => 'worker',
            'jwt' => Str::random(25),
            'active' => 1,
            'name' => $input->name,
            'email' => $input->email,
            'phone' => $input->phone,
            'password' => Hash::make($input->password),
            'cat_id' => implode(',',$input->cat_id),
            'provider_id' => 1,
            'lat' => $input->lat,
            'lng' => $input->lng,
            'city_id' => $input->city_id,
            'accept' => 1,
            'id_image' => $input->id_image,
            'image' => $input->image,
            'description' => $input->description,
        ]);
    }

    public function getWorkerCompany($cat_id)
    {
        return Worker::where('provider_id',1)->select('id','name')->where('cat_id','like','%'.$cat_id.'%')
            ->where('active', 1)->where('accept',1)->where('busy', 0)->where('online',1)->get();
    }

    public function changStatus($worker_id)
    {
        $worker = Worker::whereId($worker_id)->select('id','active')->first();

        if($worker->active == 1)
        {
            $worker->update(['active'=>0]);
            return 'suspend';
        }
        else{
            $worker->update(['active'=>1]);
            return 'active';
        }
    }

    public function activeContract($worker_id)
    {
        $worker = Worker::whereId($worker_id)->select('id','accept')->first();

        $worker->update(['accept'=>1]);
    }

    public function updateWorker($input)
    {
        $worker = Worker::where('id', $input->worker_id)->first();

        $workers=DB::table("workers")->where('id', $input->worker_id)->first();


        /*if($input->cat_id) $worker->cat_id = implode(',',$input->cat_id);*/
        if($input->cat_id) $worker->cat_id = $workers->cat_id . "," . implode(',',$input->cat_id);
        $worker->name = $input->name;
        $worker->email = $input->email;
        $worker->phone = $input->phone;
        if($input->image)
        {
            $worker->image = $input->image;
        }

        if($input->id_image)
        {
            $worker->id_image = $input->id_image;
        }

        $worker->save();
    }

    public function search($input)
    {
        $search = Input::get('search');

        return Worker::where(function($q) use($search)
        {
            $q->where('name','like','%'.$search.'%');
        }
        )->get();
    }

    public function showAdminContractPdf()
    {
        return AdminWorkerPdf::all();
    }

    public function uploadAdminContractPdf($input)
    {
        AdminWorkerPdf::create([
            'type' => $input->type,
            'file' => $input->file,
        ]);
    }

    public function editAdminContractPdf($input)
    {
        AdminWorkerPdf::where('id',$input->contract_id)->update([
            'type' => $input->type,
            'file' => $input->file,
        ]);
    }
}
