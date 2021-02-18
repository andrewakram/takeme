<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class WebController extends Controller
{
    public function webIndex(){
        return view('web.index');
    }
    
    public function provideService(){
      //  return redirect(route('provideService'));
        return view('web.provide-service');
    }

    public function webLogin(){
        return view('web.login');
    }

    public function webLoginFunc(){
        $user=DB::table("users")
            ->where("phone",request("phone"))
            ->where("password",request("password"));
        if(sizeof($user) > 0){
            return route('webRegister');
        }
        return back();
    }

    public function webRegister(){
        return view('web.register');
    }

    public function webRegisterCompany(){
        return view('web.register-company');
    }

    public function webError404(){
        return view('web.error-404');
    }

    public function webAboutUs(){
        return view('web.about-us');
    }

    public function webCategories(){
        return view('web.categories');
    }

    public function webForgetPassword(){
        return view('web.forget-password');
    }

    public function webForgetCode(){
        return view('web.forget-code');
    }

    public function webNewPassword(){
        return view('web.new-password');
    }

    public function webActiveRequests(){
        return view('web.active-requests');
    }

    public function webCompanies(){
        return view('web.companies');
    }

    public function webOrderForm(){
        return view('web.order-form');
    }

    public function webTermsCondition(){
        return view('web.terms-condition');
    }

    public function webContactUs(){
        return view('web.contact-us');
    }

    public function webSuggestion(){
        return view('web.suggestion');
    }

    public function webChat(){
        return view('web.chat');
    }



}
