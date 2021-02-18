<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\AdminRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    protected $adminRepository;
    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        $get_admin = $this->adminRepository->index();
        $admins = $get_admin['admin'];
        $data = $get_admin['data']['all_permissions'];
        return view('admin.admins.index',compact('admins','data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required|min:6',
            'image' => 'required'
        ]);

        $admin = $this->adminRepository->add($request);
        if($admin == 'email_exist')
            return redirect(route('admins.index'))->with('error','Email already exist!');
        else if($admin == 'phone_exist')
            return redirect(route('admins.index'))->with('error','Phone already exist!');
        else
            return redirect(route('admins.index'))->with('success','Admin created successfully');
    }

    public function show($id)
    {
        $get_admin = $this->adminRepository->show($id);
        $admin = $get_admin['admin'];
        $permission = $get_admin['permission'];
        $data = $get_admin['data']['all_permissions'];
        return view('admin.admins.show',compact('admin','permission','data'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $admin = $this->adminRepository->update($request,$id);
        if($admin == 'email_exist')
            return back()->with('error', 'Sorry,email already exists,please change to another one');
        elseif($admin == 'phone_exist')
            return back()->with('error', 'Sorry,phone already exists,please change to another one');
        else
            return back()->with('success', 'Admin edited successfully');

    }

    public function destroy($id)
    {
        //
    }
}
