<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\UserRepositoryInterface;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($type)
    {
        if($type == 'active') $users = $this->userRepository->activeUsers()->paginate(20);
        else $users = $this->userRepository->suspendedUsers()->paginate(20);

        return view('admin.users.index', compact('users','type'));
    }

    public function create()
    {
       //
    }

    public function store(Request $request)
    {
        $user = $this->userRepository->CreateUser($request);
        if($user == 'email_exist')
            return redirect(route('users_view','active'))->with('error','Email already exist!');
        else if($user == 'phone_exist')
            return redirect(route('users_view','active'))->with('error','Phone already exist!');
        else
            return redirect(route('users_view','active'))->with('success','Successfully created.');
    }

    public function show($id)
    {
        $user = $this->userRepository->profile($id);
        return view('admin.users.show',compact('user'));
    }

    public function changeStatus(Request $request)
    {
        $this->validate($request,[
           'user_id' => 'required|exists:users,id'
        ]);

        $user = $this->userRepository->changStatus($request->user_id);
        if($user == 'suspend')
            return back()->with('success','User suspended successfully');
        else
            return back()->with('success','User activated successfully');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $users = $this->userRepository->search($request);
        $type='';
        return view('admin.users.index',compact('users','type'));
    }
}
