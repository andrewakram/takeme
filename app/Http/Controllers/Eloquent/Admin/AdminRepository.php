<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 28/07/2019
 * Time: 02:42 م
 */

namespace App\Http\Controllers\Eloquent\Admin;


use App\Http\Controllers\Interfaces\Admin\AdminRepositoryInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminRepository implements AdminRepositoryInterface
{
    public function index()
    {
        $admin = Admin::where('id','!=',1)->get();
        $permissions = Permission::get();
        $data['all_permissions'] = [
            'Dashboard' => [$permissions[0]],
            'Users' => [$permissions[1],$permissions[2],$permissions[3],$permissions[4],$permissions[5]],
            'Company Workers' => [$permissions[6],$permissions[7],$permissions[8],$permissions[9],$permissions[10]],
            'App Workers' => [$permissions[11],$permissions[12],$permissions[13],$permissions[14],$permissions[15]],
            'Admins' => [$permissions[16],$permissions[17],$permissions[18],$permissions[19],$permissions[20]],
            'Categories' => [$permissions[21],$permissions[22],$permissions[23],$permissions[24],$permissions[25],$permissions[26],$permissions[27]],
            'Orders' => [$permissions[28],$permissions[29],$permissions[30]],
            'Settings' => [$permissions[31],$permissions[32]],
            'Cities' => [$permissions[33],$permissions[34],$permissions[35],$permissions[36]],
        ];
        return ['admin'=>$admin,'data'=>$data];
    }

    public function show($id)
    {
        $admin =  Admin::whereId($id)->first();

        $permissions = Permission::get();
        $data['title'] = "تعديل مشرف";
        $data['admin'] = $admin;
        $data['permissions'] = $permissions;
        $data['all_permissions'] = [
            'Dashboard' => [$permissions[0]],
            'Users' => [$permissions[1],$permissions[2],$permissions[3],$permissions[4],$permissions[5]],
            'Company Workers' => [$permissions[6],$permissions[7],$permissions[8],$permissions[9],$permissions[10]],
            'App Workers' => [$permissions[11],$permissions[12],$permissions[13],$permissions[14],$permissions[15]],
            'Admins' => [$permissions[16],$permissions[17],$permissions[18],$permissions[19],$permissions[20]],
            'Categories' => [$permissions[21],$permissions[22],$permissions[23],$permissions[24],$permissions[25],$permissions[26],$permissions[27]],
            'Orders' => [$permissions[28],$permissions[29],$permissions[30]],
            'Settings' => [$permissions[31],$permissions[32]],
            'Cities' => [$permissions[33],$permissions[34],$permissions[35],$permissions[36]],
        ];

//        $data['all_permissions'] = [
//            'المستخدمين' => [$permissions[0],$permissions[1]],
//            'المندوبين' => [$permissions[2],$permissions[3],$permissions[4],],
//            'الطلبات' => [$permissions[5],$permissions[6]],
//            'الأقسام' => [$permissions[7],$permissions[8],$permissions[9]],
//            'الإعدادات' => [$permissions[10],$permissions[11]],
//            'الإحصائيات' => [$permissions[12]],
//            'المشرفين' => [$permissions[13],$permissions[14],$permissions[15],$permissions[16]],
//        ];

        return ['admin'=>$admin,'permission'=>$permissions,'data'=>$data];
    }

    public function add($input)
    {
        $email = Admin::whereEmail($input->email)->select('id')->first();
        if($email)
            return 'email_exist';
        $phone = Admin::wherePhone($input->phone)->select('id')->first();
        if($phone)
            return 'phone_exist';

        $admin = Admin::create([
            'active' => 1,
            'name' => $input->name,
            'email' => $input->email,
            'phone' => $input->phone,
            'password' => Hash::make($input->password),
            'image' => $input->image,
        ]);

        $admin->syncPermissions($input->check_list);
    }

    public function update($input,$id)
    {
        $email_check = Admin::where('id','!=',$id)->where('email', $input->email)->first();
        $phone_check = Admin::where('id','!=',$id)->where('phone', $input->phone)->first();

        if($email_check) return 'email_exist';
        if($phone_check) return 'phone_exist';

        $admin = Admin::whereId($id)->first();

        $admin->name = $input->name;
        $admin->email = $input->email;
        $admin->phone = $input->phone;

        if($input->password)
            $admin->password = Hash::make($input->password);

        if($input->image)
            $admin->image = $input->image;

        $admin->save();

        $admin->syncPermissions($input->check_list);
    }
}
