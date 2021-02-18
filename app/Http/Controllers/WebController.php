<?php

namespace App\Http\Controllers;

use App\Models\ActiveRequest;
use App\Models\ClientReivew;
use App\Models\JazBenfit;
use App\Models\JazPartener;
use App\Models\JazService;
use App\Models\JazTeam;
use App\Models\JazVision;
use App\Models\Message;
use App\Models\Newsletter;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\ThirdCatOrder;
use App\Models\ComplainSuggests;
use App\Models\User;
use App\Models\Verification;
use App\Models\Worker;
use App\Models\Category;
use App\Models\Slider;
use App\Models\Album;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Session;
use App\Models\ContactUs;

class WebController extends Controller
{
    public function __construct(){
        /*if(Session::get('lang') == null){
            Session::put('lang','ar');
            app()->setLocale('ar');
        }*/
    }

    public function sendSMS($role,$type,$phone)
    {
        $activation_code = generateActivationCode();
        $message = "كود التفعيل الخاص بجاز هو".$activation_code;
        $message = urlencode($message);
        $url = "https://www.hisms.ws/api.php?send_sms&username=966563244763&password=Aa0563244763&message=$message&numbers=$phone&sender=JazApp&unicode=e&Rmduplicated=1&return=json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $decodedData = json_decode($data);

        Verification::updateOrcreate
        (
            [
                'role' => $role,
                'type' => $type,
                'phone' => $phone,
            ],
            [
                'code' => $activation_code,
                'expire_at' => Carbon::now()->addHour()->toDateTimeString()
            ]
        );
    }

    public function checkIfEmailExist($email)
    {
        return User::whereEmail($email)->select('id')->first();
    }

    public function checkIfPhoneExist($phone)
    {
        $user = User::wherePhone($phone)->select('id','password','token','active','image')->first();
        return $user;
    }

    /*ajax*/
    public function updateCartOrder(Request $request){
        if(Session::get("u_phone") != "" && Session::get("lang") != ""){
            DB::table("orders")->where("id",$request->id)
                ->where("user_id",Session::get("u_id"))
                ->update(["view_status" => 1 ]);

            ////////////////////////////////////
            global $active_orders;
            $active_orders = [];

            if(Session::get("u_phone") != ""){
                $orders = Order::whereUserId(Session::get("u_id"))
                    ->where('order_action',0)
                    ->where('order_status',"")
                    ->where('worker_id',null)
                    ->where('view_status',0)//*
                    ->select('id','date','time','type','created_at')
                    ->latest()->get();


                foreach ($orders as $order)
                {
                    $active_requests = ActiveRequest::orderBy("order_id","desc")
                        ->where('order_id',$order->id)
                        ->where('user_status',"!=",2)
                        ->pluck('order_id');
                    if(in_array($order->id,$active_requests->toArray()))
                    {
                        array_push($active_orders,$order);
                    }
                }

                foreach ($active_orders as $active_order)
                {
                    $active_req = ActiveRequest::where('order_id',$active_order->id)
                        ->where('worker_id','!=',null)
                        ->where('user_status',"!=",2)
                        ->get();

                    $active_order['applied_worker'] = count($active_req);


                    $service_data = Category::join("orders","orders.cat_id","categories.id")
                        ->where("orders.id",$active_order->id)
                        ->select("categories.image","en_name","ar_name")->first();
                    $active_order['image'] = $service_data->image;
                    $active_order['en_name'] = $service_data->en_name;
                    $active_order['ar_name'] = $service_data->ar_name;

                }
                /*return $active_orders;*/
                $active_orders_in_cart = $active_orders;
            }else{
                $active_orders_in_cart = [];
            }
            Session::put("active_orders_in_cart",$active_orders_in_cart);
            /////////////////////////////////

            return "true";
        }
    }
    /*end ajax changeOrderRate*/

    public function webIndex(){
        Session::put("thirdCat","");
        Session::put("category","");
        Session::put("type","");

        ////////////////////////////////////
        global $active_orders;
        $active_orders = [];

        if(Session::get("u_phone") != ""){
            $orders = Order::whereUserId(Session::get("u_id"))
                ->where('order_action',0)
                ->where('order_status',"")
                ->where('worker_id',null)
                ->where('view_status',0)//*
                ->select('id','date','time','type','created_at')
                ->latest()->get();


            foreach ($orders as $order)
            {
                $active_requests = ActiveRequest::orderBy("order_id","desc")
                    ->where('order_id',$order->id)
                    ->where('user_status',"!=",2)
                    ->pluck('order_id');
                if(in_array($order->id,$active_requests->toArray()))
                {
                    array_push($active_orders,$order);
                }
            }

            foreach ($active_orders as $active_order)
            {
                $active_req = ActiveRequest::where('order_id',$active_order->id)
                    ->where('worker_id','!=',null)
                    ->where('user_status',"!=",2)
                    ->get();

                $active_order['applied_worker'] = count($active_req);


                $service_data = Category::join("orders","orders.cat_id","categories.id")
                    ->where("orders.id",$active_order->id)
                    ->select("categories.image","en_name","ar_name")->first();
                $active_order['image'] = $service_data->image;
                $active_order['en_name'] = $service_data->en_name;
                $active_order['ar_name'] = $service_data->ar_name;

            }
            /*return $active_orders;*/
            $active_orders_in_cart = $active_orders;
        }else{
            $active_orders_in_cart = [];
        }
        /////////////////////////////////
        $lang=Session::get("lang");
        ///
        ////////////// about us ///////////////////
        $abouts=DB::table("about_us")->select($lang."_name as name")->first();
        Session::put("about",$abouts->name);
        ///////////////////////////////////////////
        ////////////// Slider /////////////////////
        $slider=Slider::orderBy("id","asc")
            ->select("title_".$lang. " as title","body_".$lang. " as body","image")->get();
        ///////////////////////////////////////////

        Session::put("active_orders_in_cart",$active_orders_in_cart);
        $main_cats= DB::table("categories")
            ->orderBy("id","asc")
            ->where("parent_id",NULL)
            ->select($lang."_name as name","id","image","description")
            ->get();

        $ourServices= JazService::orderBy("id","asc")
            ->select("name_".$lang." as name","id","image","description_".$lang." as description")
            ->get();

        $services= Category::orderBy("id","asc")
            ->where('type',4)
            ->select($lang."_name as name","id","image","description")
            ->get();

        $jazBenfits= JazBenfit::orderBy("id","asc")
            ->select("title1_".$lang." as title1",
                "title2_".$lang." as title2",
                "title3_".$lang." as title3",
                "title4_".$lang." as title4",
                "body1_".$lang." as body1",
                "body2_".$lang." as body2",
                "body3_".$lang." as body3",
                "body4_".$lang." as body4"
                )
            ->first();
        $jazVision= JazVision::orderBy("id","asc")
            ->select("body_".$lang." as body",
                "vision1_".$lang." as vision1",
                "vision2_".$lang." as vision2",
                "vision3_".$lang." as vision3",
                "vision4_".$lang." as vision4"
            )->first();
        $reviews= ClientReivew::orderBy("id","asc")
            /*->select("name_".$lang." as name","id","image","comment_".$lang." as comment")*/
            ->select("name","job","id","image","comment_".$lang." as comment")
            ->get();
        $teams= JazTeam::orderBy("id","asc")
            /*->select("name_".$lang." as name","id","image","comment_".$lang." as comment")*/
            ->select("name","id","image","job","facebook","twitter","youtube","instgram","linkedin")
            ->get();
        $parteners= JazPartener::orderBy("id","asc")
            /*->select("name_".$lang." as name","id","image","comment_".$lang." as comment")*/
            ->select("id","image","link")
            ->get();
        $albums= Album::orderBy("id","asc")
            /*->select("name_".$lang." as name","id","image","comment_".$lang." as comment")*/
            ->select("id","smallimage","largeimage","cat_id")
            ->get();

            Session::put("active","home");

        return view('web.content',[
            "main_cats" => $main_cats,
            "ourServices" => $ourServices,
            "services" => $services,
            "jazBenfits" => $jazBenfits,
            "jazVision" => $jazVision,
            "reviews" => $reviews,
            "teams" => $teams,
            "parteners" => $parteners,
            "albums" => $albums,
            "active_orders" => $active_orders_in_cart,
            "abouts" => $abouts,
            "slider" => $slider,
        ]);
    }

    public function changeViewStatusInCart(Request $request,$order_id){
        Order::whereUserId(Session::get("u_id"))
            ->where('id',$order_id)
            ->update(['view_status',1]);

        ////////////////////////////////////
        if(Session::get("u_phone") != ""){
            $orders = Order::whereUserId(Session::get("u_id"))
                ->where('order_action',0)
                ->where('worker_id',null)
                ->where('order_status',"")
                ->where('view_status',0)//*
                ->select('id','date','time','type','created_at')
                ->latest()->get();
            global $active_orders;
            $active_orders = [];
            foreach ($orders as $order)
            {
                $active_requests = ActiveRequest::orderBy("order_id","desc")
                    ->where('order_id',$order->id)
                    ->where('sent_worker_id',"!=",NULL)//*
                    ->where('user_status',"!=",2)//*
                    ->pluck('order_id',"worker_id");
                if(in_array($order->id,$active_requests->toArray()))
                {
                    array_push($active_orders,$order);
                }
            }

            foreach ($active_orders as $active_order)
            {
                $active_req = ActiveRequest::where('order_id',$active_order->id)
                    ->where('sent_worker_id',"!=",NULL)//*
                    ->where('worker_id',NULL)//*
                    ->where('user_status',"!=",2)
                    ->get();

                $active_order['applied_worker'] = count($active_req);


                $service_data = Category::join("orders","orders.cat_id","categories.id")
                    ->where("orders.id",$active_order->id)
                    ->select("categories.image","en_name","ar_name")->first();
                $active_order['image'] = $service_data->image;
                $active_order['en_name'] = $service_data->en_name;
                $active_order['ar_name'] = $service_data->ar_name;

            }
            /*return $active_orders;*/
            $active_orders_in_cart = $active_orders;
        }else{
            $active_orders_in_cart = [];
        }
        /////////////////////////////////

        Session::put("active_orders_in_cart",$active_orders_in_cart);
    }

    public function provideService(){
      //  return redirect(route('provideService'));
        return view('web.provide-service');
    }

    public function webLogin(){
        return view('web.login');
    }

    public function webLoginFunc(Request $request){
        /*$data = $this->validate(request(),
            [
                'phone'     =>'required',
                'password'  =>'required',
            ],[],
            [
                'phone'     =>'Phone',
                'password'  =>'Password',
            ]
        );*/
        $userData = User::where("phone"   ,    "=", $_POST['phone'])
            ->where("active",      "!=", 0)
            ->first();
        if(($userData) && Hash::check(($_POST['password']),$userData->password)){
            Session::put('u_id',$userData->id);
            Session::put('u_email',$userData->email);
            Session::put('u_phone',$userData->phone);
            Session::put('u_name',$userData->name);
            Session::put('u_image',$userData->image);
            Session::put('lat',$userData->lat);
            Session::put('lng',$userData->lng);
            Session::put('user',"1");
            if(Session::get('lang') == "en") {
                return redirect(route('webIndex'));
            }else{
                return redirect(route('webIndex'));
            }
        }else{
            if(Session::get('lang') == "en") {
                session()->flash('insert_message','Wrong email or password');
                return redirect('/log-in');
            }else{
                session()->flash('insert_message','خطأ في البريد الالكتروني او كلمة المرور');
                return redirect('/log-in');
            }
        }
    }

    public function webRegisterFunc(Request $request){

        $validator = Validator::make($request->all(), [
            'role' => 'required|in:user,company',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|numeric',
            'password' => 'required|min:6',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect("/");
        }

        $email = $request->email;

        if($this->checkIfEmailExist($email))
        {
            return redirect("/");
        }

        $phone = $request->phone;

        if($this->checkIfPhoneExist($phone))
        {
            return redirect("/");
        }

        $array = array(
            'jwt' => Str::random(25),
            'role' => $request->role,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'lat' => $request->lat,
            'lng' => $request->lng,
            'address' => $request->address,
        );

        if($user = User::create($array))
        {
            if($request->image)
            {
                $user->image = $request->image;
            }

            if($request->commercial_register)
            {
                $user->commercial_register = $request->commercial_register;
            }

            $this->sendSMS('user', 'activate', $user->phone);
            $user->save();
            $phone=$request->phone;
            $role=$request->role;
            return view("web.code",[
                "phone" => $phone,
                "role" => $role
            ]);
        }

    }

    public function codeCheck($code)
    {
        return Verification::whereCode($code)->whereRole('user')->first();
    }

    public function activeUser($phone)
    {
        $user = $this->checkIfPhoneExist($phone);
        $user->active = 1;
        $user->save();
        return $user;
    }

    public function webCheckCodeFunc(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if($validator->fails())
        {
            return redirect(route('webIndex'));
        }

        $check = $this->codeCheck($request->code);
        if($check)
        {
            if(Carbon::now()->format('Y-m-d H') > Carbon::parse($check->expire_at)->format('Y-m-d H'))
            {
                return response()->json(msg($request, failed(), 'code_expire'));
            }else{
                if($check->type == 'activate')
                {
                    $this->activeUser($check->phone);
                    /*return response()->json(msg($request, success(), 'activated'));*/
                    $userData = DB::table('users')
                        ->where("phone"   ,    "=", $request->phone)
                        ->first();
                    Session::put('u_id',$userData->id);
                    Session::put('u_email',$userData->email);
                    Session::put('u_phone',$userData->phone);
                    Session::put('u_name',$userData->name);
                    Session::put('lat',$userData->lat);
                    Session::put('lng',$userData->lng);
                    Session::put('user',"1");
                    return redirect(route('webIndex'));
                }else{
                    return redirect(route('webIndex'));
                }
            }
        }else{
            return redirect(route('webIndex'));
        }
    }

    public function webForgetPassFunc(Request $request){
        $phone= $request->phone;
        $activation_code = generateActivationCode();
        $message = "كود التفعيل الخاص بجاز هو".$activation_code;
        $message = urlencode($message);
        $url = "https://www.hisms.ws/api.php?send_sms&username=966563244763&password=Aa0563244763&message=$message&numbers=$phone&sender=JazApp&unicode=e&Rmduplicated=1&return=json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $decodedData = json_decode($data);

        Verification::updateOrcreate
        (
            [
                'role' => "user",
                'type' => "reset",
                'phone' => $phone,
            ],
            [
                'code' => $activation_code,
                'expire_at' => Carbon::now()->addHour()->toDateTimeString()
            ]
        );
        $role="user";
        $type="reset";
        return view("web.forget-code",[
            "phone" => $phone,
            "role" => $role,
            "type" => $type
        ]);
    }

    public function webResetNewPass(Request $request){
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'phone' => 'required',
            'code' => 'required',
            'type' => 'required',

        ]);

        if($validator->fails())
        {
            return redirect("/");
        }
        $phone= $request->phone;
        $role= $request->role;
        $type= $request->type;
        $code= $request->code;
        $data = User::join("verifications","verifications.phone","users.phone")
            ->where('verifications.phone',$phone)
            ->where('verifications.role',$role)
            ->where('verifications.type',$type)
            ->where('verifications.code',$code)
            ->first();
        if($data) {
            return view("web.new-password",[
                "role" => $role,
                "phone" => $phone
            ]);
        }else{
            return redirect(route('webIndex'));
        }
    }

    public function webResetNewPassFunc(Request $request){
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'phone' => 'required',
            'pass' => 'required',
            'confirm' => 'required',

        ]);

        if($validator->fails())
        {
            return redirect("/");
        }
        $phone= $request->phone;
        $role= $request->role;
        $pass= $request->pass;
        $confirm= $request->confirm;
        if($pass == $confirm){
            User::join("verifications","verifications.phone","users.phone")
                ->where('verifications.phone',$phone)
                ->where('verifications.role',$role)
                ->update([
                    'password' => Hash::make($pass),
                ]);
            $newData= User::where('phone',$phone)
                ->where('role',$role)
                ->select('id','name','email','phone','lat'.'lng')
                ->first();
            if($newData) {
                Session::put('u_id',$newData->id);
                Session::put('u_email',$newData->email);
                Session::put('u_phone',$newData->phone);
                Session::put('u_name',$newData->name);
                Session::put('lat',$newData->lat);
                Session::put('lng',$newData->lng);

                Session::put('user',"1");
                return redirect(route('webIndex'));
            }else{
                return redirect(route('webIndex'));
            }
            return redirect(route('webIndex'));
        }
    }

    public function webLogout(){
        Session::put('u_id',"");
        Session::put('u_email',"");
        Session::put('u_phone',"");
        Session::put('u_name',"");
        Session::put('user',"");
        Session::put('lat',"");
        Session::put('lng',"");

        return redirect("/");
    }

    public function webRegister(){
        return view('web.register');
    }

    public function webRegisterCompany(){
        return view('web.register-company');
    }

    public function webCategories(Request $request,$main_cat){
        if($main_cat == 3){
            Session::put("thirdCat",3);
        }
        $categories=DB::table("categories")
            ->orderBy("id","asc")
            ->where("parent_id",$main_cat)
            ->where("type",2)
            ->get();
        Session::put("category",$main_cat);
        return view('web.categories',["categories" => $categories]);
    }

    public function webgetSubCategories(Request $request,$main_cat){
        /*ajax*/
        $arr=[];
        $mycat=Category::where("id",$main_cat)->first();
        $categories=Category::orderBy("id","asc")
            ->where("parent_id",$main_cat)
            ->where("type",$mycat->type +1)
            ->where("active",1)
            ->select("id",Session::get('lang').'_name as name','image','description','type')
            ->get();
        foreach ($categories as $sub){
            $third_cats = Category::whereType($mycat->type +2)
                ->where('parent_id', $sub->id)
                ->where("active",1)
                ->first();
            if($third_cats){
                $sub->hasSubCats = true;
            }else{
                $sub->hasSubCats = false;
            }
            array_push($arr,$sub);
        }
        /*if($categories)*/
        return response()->json($arr);
        /*else return redirect("/en");*/
    }

    public function webChooseOrderChoice(Request $request,$cat_id){

        Session::put("category",$cat_id);
        return view('web.order-choice',['cat_id'=>$cat_id]);
    }

    public function webOrderChoice(Request $request,$cat_id,$choice){

        if($choice > 2 OR $choice < 1)
            return back();

        ///// check if service has period time
        $isPeriod=Category::whereId($cat_id)
            ->select('has_period')->first()->has_period;
        if($isPeriod == true)
            Session::put("thirdCat","has_period");
        /////

        Session::put("choice",$choice);
        if(Session::get("thirdCat") == 3 OR
            Session::get("thirdCat") == "has_period"){
            Session::put("category",$cat_id);
            return view('web.order-form');
        }

        return view('web.service-type');
    }

    public function webServiceType(Request $request,$cat_id){
        if(Session::get("thirdCat") == 3){
            Session::put("category",$cat_id);

            $third_cats = Category::whereType(3)
                ->where('parent_id', $cat_id)
                ->get();

            return view('web.company-trucks',["third_cats" => $third_cats]);
        }
        Session::put("category",$cat_id);
        return view('web.order-choice',['cat_id'=>$cat_id]);
    }

    public function webOrderForm(Request $request,$type){
        Session::put("type",$type);
        if(Session::get("thirdCat") == 3){
            Session::put("category",$request->send);
            return view('web.order-form');
        }
        if(Session::get("u_phone") != ""){
            if(Session::get("type") == "urgent"){
                return view('web.order-form-urgent');
            }elseif(Session::get("type") == "schedule"){
                return view('web.order-form');
            }
        }else{
            return redirect(route('webLogin'));
        }
    }

    public function webMakeOrderForm(Request $request){
        dd($request->all());

        if(Session::get("u_phone") != ""){

            $validator = Validator::make($request->all(),[
                /*'type' => 'required|in:urgent,scheduled',*/
                /*'cat_id' => 'required|exists:categories,id',*/
                /*'third_cat_id' => 'sometimes|exists:categories,id,type,3',*/
                /*'user_id' => 'required|exists:users,id',*/
                'address' => 'required',
                'lat' => 'required',
                'lng' => 'required',
                'description' => 'required|max:191',
                /*'date' => 'sometimes',
                'time' => 'sometimes',
                'hours' => 'sometimes|numeric',
                'image' => 'sometimes',
                'video' => 'sometimes',*/
            ]);

            if($validator->fails())
            {
                return redirect("/");
                return response()->json(['status' => 'error', 'msg' => $validator->messages()]);
            }
            ///////////////////// create order //////////////////////
            $array = array(
                'type' => Session::get("type"),
                'cat_id' => Session::get("category"),
                'order_choice' => Session::get("choice"),
                'user_id' => Session::get("u_id"),
                'address' => $request->address,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'description' => $request->description,
                'date' => $request->date,
                'time' => $request->time,
            );

            $order = Order::create($array);

            if($order)
            {
                if($request->image)
                {
                    foreach ($request->image as $image)
                    {
                        OrderImage::create([
                            'order_id' => $order->id,
                            'type' => 'image',
                            'media' => $image
                        ]);
                    }
                }
                if($request->video)
                {
                    OrderImage::create([
                        'order_id' => $order->id,
                        'type' => 'video',
                        'media' => $request->video
                    ]);
                }

                ActiveRequest::create([
                    'order_id' => $order->id
                ]);

                ////////////////// end order create ///////////////////
                ///
                ////////////////// check order has period ///////////////////
                if(Session::get("thirdCat") == "has_period"){
                    ThirdCatOrder::create
                    ([
                        'order_id' => $order->id,
                        'cat_id' => $order->cat_id,
                        'hours' => (int)request("hours") * (int)request("units")
                    ]);
                }
                ////////////////// check order third cat ///////////////////
                if(Session::get("thirdCat") == 3){

                    ///////////////////////////////////
                    ThirdCatOrder::create
                    ([
                        'order_id' => $order->id,
                        'cat_id' => $order->cat_id,
                        'hours' => (int)request("hours") * (int)request("units")
                    ]);

                    $order = Order::whereId($order->id)->select('id','user_id','cat_id')
                        ->first();

                    $filter_order = Order::filterbylatlng($order->user->lat,$order->user->lng,50,'workers',$order->cat_id);
                    if(count($filter_order) > 0)
                    {
                        $ar_message = 'لديك طلب خدمة جديد,الرجاء الإستجابة';
                        $en_message = 'You have a new order request,please respond';

                        foreach ($filter_order as $filter)
                        {
                            Notification::create([
                                'user_id' => $order->user_id,
                                'worker_id' => $filter->id,
                                'order_id' => $order->id,
                                'ar_message' => $ar_message,
                                'en_message' => $en_message,
                                'send_to' => 'worker'
                            ]);

                            $active_request = ActiveRequest::where('order_id',$order->id)->first();

                            if($active_request->sent_worker_id == null)
                            {
                                $active_request->update([
                                    'sent_worker_id' => $filter->id
                                ]);
                            }else
                            {
                                ActiveRequest::create([
                                    'sent_worker_id' => $filter->id,
                                    'order_id' => $order->id
                                ]);
                            }

                            $worker = Worker::whereId($filter->id)->pluck('token');
                            Notify::send($worker,$ar_message,$en_message,'third_cat');
                        }
                        return redirect()->route("webActive-requests");
                    }
                    //////////////////////////////////
                }
            }
            return redirect()->route("webActive-requests");
        }else{
            return redirect(route('webLogin'));
        }
    }

    Public function webHistory(){}

    public function webError404(){
        return view('web.error-404');
    }

    public function webAboutUs(){
        Session::put("active","about");
        $lang=Session::get("lang");
        $abouts=DB::table("about_us")->select($lang."_name as name")->first();
        return view('web.about-us',["abouts" => $abouts]);
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
        Session::put("active","orders");
        $lang=Session::get("lang");
        ////////////// about us ///////////////////
        $abouts=DB::table("about_us")->select($lang."_name as name")->first();
        ///
        if(Session::get("u_phone") != ""){
            $orders = Order::whereUserId(Session::get("u_id"))
                ->where('order_action',0)
                ->where('order_status',"")
                ->where('worker_id',null)
                ->select('id','date','time','type','created_at')
                ->latest()->get();
            $active_orders = [];
            foreach ($orders as $order)
            {
                $active_requests = ActiveRequest::orderBy("order_id","desc")
                    ->where('order_id',$order->id)
                    ->where('user_status',"!=",2)
                    ->pluck('order_id');
                if(in_array($order->id,$active_requests->toArray()))
                {
                    array_push($active_orders,$order);
                }
            }

            foreach ($active_orders as $active_order)
            {
                $active_req = ActiveRequest::where('order_id',$active_order->id)
                    ->where('worker_id','!=',null)
                    ->where('user_status',"!=",2)
                    ->get();

                $active_order['applied_worker'] = count($active_req);

                $service_data = Category::join("orders","orders.cat_id","categories.id")
                    ->where("orders.id",$active_order->id)
                    ->select("categories.image","en_name","ar_name")->first();
                $active_order['image'] = $service_data->image;
                $active_order['en_name'] = $service_data->en_name;
                $active_order['ar_name'] = $service_data->ar_name;

            }
            /*return $active_orders;*/

            $orders2 = Order::orderBy("id","desc")
                ->whereUserId(Session::get("u_id"))
                ->where('worker_id',"!=",null)
                ->where('order_status',"!=","")
                ->select('id','date','time','type','created_at',"worker_id","order_status as status")
                ->get();
            $orderHistory = [];
            foreach($orders2 as $o2){
                $workers= Worker::where("id",$o2->worker_id)->first();
                $o2["worker_id"]=$workers->id;
                $o2["worker_name"]=$workers->name;

                $service_data = Category::join("orders","orders.cat_id","categories.id")
                    ->where("orders.id",$active_order->id)
                    ->select("categories.image","en_name","ar_name")->first();
                $o2['image'] = $service_data->image;
                $o2['en_name'] = $service_data->en_name;
                $o2['ar_name'] = $service_data->ar_name;

                array_push($orderHistory,$o2);
            }


            return view('web.active-requests',[
                "active_orders" => $active_orders,
                "orderHistory"  => $orderHistory,
            ]);
        }else{
            return redirect(route('webLogin'));
        }
    }

    public function webOrderTracking($id){
        if(Session::get("u_phone") != ""){
            $orderDetails = Order::whereUserId(Session::get("u_id"))
                ->where("id",$id)
                /*->where('order_action',0)*/
                ->where('worker_id',"!=",null)
                ->where('order_status',"!=","")
                ->select('id','date','time','type','created_at',
                    "worker_id","order_status as status","address",
                    "order_total","description","rate")
                ->get();
            $orders = [];
            foreach($orderDetails as $od){
                $workers= Worker::where("id",$od->worker_id)->first();
                $od['image'] = $workers->image;
                $od['name'] = $workers->name;

                array_push($orders,$od);
            }


            return view('web.order-tracking',["orders"=>$orders]);
        }else{
            return redirect(route('webLogin'));
        }
    }

    /*ajax*/
    public function changeOrderRate(Request $request) {
        if(Session::get("u_phone") != "" && Session::get("lang") != ""){
            DB::table("orders")->where("id",$request->orderIdValue)
                ->where("user_id",Session::get("u_id"))
                ->update(["rate" => $request->rateValue ]);
            return "true";
        }
    }
    /*end ajax changeOrderRate*/

    public function webcancelOrder(){
        $id=request('order_id');
        if(Session::get("u_phone") != ""){
            $data = $this->validate(request(), [
                'cancelReason'          => 'required',
            ],[],[
                'cancelReason'          =>'cancel Reason',
            ]);

            $order = Order::whereId($id)
                ->whereUserId(Session::get("u_id"))
                ->select('id','user_id','worker_id','order_action','order_status','order_total')->first();

            if($order->order_status == 'on_way' || $order->order_status == 'finish_order') {
                return redirect(route("webIndex"));
            }else {
                $order2 = Order::whereId($id)
                    ->whereUserId(Session::get("u_id"))
                    ->select('id', 'user_id', 'worker_id', 'order_action', 'order_status', 'order_total')
                    ->get();
                foreach ($order2 as $o) {
                    $active_request = ActiveRequest::where('order_id', $id)
                        ->where('worker_id', $o->worker_id)->first();
                    $active_request->update([
                        'user_status' => 2
                    ]);
                }
                $order->order_action = 2;
                $order->order_status = 'user_cancelling';
                $order->cancel_reason = request('cancel_reason');
                $order->order_total = 0;
                $order->save();
                Worker::where("id", $order->worker_id)->update(["busy" => 0]);

                if ($order->worker_id != NULL) {
                    $ar_message = 'قام المستخدم بالغاء الطلب.';
                    $en_message = 'User cancel the order';

                    $token = Worker::whereId($order->worker_id)->pluck('token');
                    Notification::create
                    ([
                        'user_id' => $order->user_id,
                        'worker_id' => $order->worker_id,
                        'order_id' => $order->id,
                        'ar_message' => $ar_message,
                        'en_message' => $en_message,
                        'send_to' => 'worker',
                    ]);
                    Notify::send($token, '', $ar_message, $en_message, 'order', $order->id);
                }
            }
            return back();
        }else{
            return redirect(route('webLogin'));
        }
    }

    public function webAcceptedCompanies(){
        $lang=Session::get("lang");
        ////////////// about us ///////////////////
        $abouts=DB::table("about_us")->select($lang."_name as name")->first();
        ///
        Session::put("active","companies");
        $companies=DB::table("workers")->where("role","company")->paginate(5);
        return view('web.acceptedCompanies',["companies"=>$companies]);
    }

    public function webViewCompaniesOffers(Request $request,$id){
        if(Session::get("u_phone") != ""){
            $this->updateCartOrder($request);

            $acceptedWorkers = ActiveRequest::orderBy("order_id","desc")
                ->where('order_id',$id)
                ->where('user_status',"!=",2)
                ->where('worker_id',"!=",NULL)
                ->pluck('worker_id');
            $companies=DB::table("workers")->whereIn("id",$acceptedWorkers)->paginate(5);
            return view('web.companies',[
                "companies"=>$companies,
                "order_id"=>$id,
            ]);
        }else{
            return redirect(route('webLogin'));
        }
    }

    public function webCompany($id){
        $companies=DB::table("workers")->where("id",$id)->get();
        return view('web.company',["companies"=>$companies]);
    }

    public function webTermsCondition(){
        $lang=Session::get("lang");
        ////////////// about us ///////////////////
        $abouts=DB::table("about_us")->select($lang."_name as name")->first();
        ///
        Session::put("active","terms");
        $terms=DB::table("term_conditions")->get();
        return view('web.terms-condition',["terms" => $terms]);
    }

    public function webContactUs(){
        $lang=Session::get("lang");
        ////////////// about us ///////////////////
        $abouts=DB::table("about_us")->select($lang."_name as name")->first();
        ///
        Session::put("about",$abouts->name);
        Session::put("active","contact");
        return view('web.contact-us');
    }

    public function webSuggestion(){
        return view('web.suggestion');
    }

    public function webAddSuggestion(Request $request){
        if(Session::get("u_id")){
            $add = new ComplainSuggests();
            $add->type = "suggest";
            $add->user_id = Session::get("u_id");
            $add->title = $request->title;
            $add->description = $request->Description;
            $add->save();
        }

        return redirect(Session::get("lang")."/");
    }

    public function webChat($company_id,$order_id){
        $newChat=[];
        $checkMessages=DB::table("messages")
            ->where("order_id",$order_id)->get();
            if($checkMessages){
                $newChat=DB::table("workers")
            ->select("id as worker_id","name","image")
            ->where("id",$company_id)
            ->get();
            }


        $u_id=Session::get("u_id");
        $chatList=DB::select('
            select messages.id,worker_id,order_id,body,send,is_read,
            workers.name,workers.image
            from messages
            join workers on workers.id = messages.worker_id
            WHERE messages.id IN (
                select max(id)
                from messages
                where user_id = "'.$u_id.'"
                group by `worker_id`
                )
            ');
        return view('web.chat',[
            "chatList" => $chatList,
            "newChat" => $newChat,
            "order_id" => $order_id
        ]);
    }

    public function getChatUpdates22(Request $request){
        $user=$request->user_id;
        $messages=DB::select('
            select worker_id,is_read
            from messages
            join workers on workers.id = messages.worker_id
            WHERE messages.id IN (
                select max(id)
                from messages
                where user_id = "'.$user.'"
                group by `worker_id`
                )
            ');
        return $messages;
        /*return response()->json($messages);*/
    }

    public function getMessages22(Request $request){
        DB::table("messages")
            ->where("messages.worker_id",$request->worker_id)
            ->where("messages.user_id",$request->user_id)
            ->update(["is_read" => 1]);

        $messages = DB::table("messages")
            ->join("workers","workers.id","messages.worker_id")
            ->where("messages.worker_id",$request->worker_id)
            ->where("messages.user_id",$request->user_id)
            ->pluck('send','body');

        return response()->json($messages);
    }

    public function notification($user_id, $worker_id, $ar_message, $en_message,$send_to)
    {
        Notification::create
        ([
            'user_id' => $user_id,
            'worker_id' => $worker_id,
            'ar_message' => $ar_message,
            'en_message' => $en_message,
            'send_to' => $send_to,
        ]);
    }

    public function addMessages22(Request $request){
        $order=DB::table("messages")
            ->where("messages.worker_id",$request->worker_id)
            ->where("messages.user_id",$request->user_id)
            ->first();


        $ar_message = 'رسالة جديدة.';
        $en_message = 'New Message.';


            $image = User::whereId($request->user_id)->select('image')->first();
            $this->notification($request->user_id,$request->worker_id,$ar_message,$en_message,'worker');
            $token = Worker::whereId($request->worker_id)->pluck('token');
            Notify::send($token,null,$ar_message,$en_message,'message',$request->order_id,$request->worker_id,'',$image->image,$request->user_id,$request->message);


        $add                = new Message();
        $add->body          = $request->message;
        $add->send          = "user";
        $add->is_read       = 1;
        $add->worker_id     = $request->worker_id;
        $add->user_id       = $request->user_id;
        $add->order_id      = $request->order_id;
        $add->save();
        return response()->json("done");
    }

    public function editProfile(Request $request){
        return view('web.edit-profile');
    }

    public function updateProfileData(Request $request){
        $user = User::whereId($request->u_id)->first();

        $user->name = $request->u_name;
        $user->lat = $request->lat;
        $user->lng = $request->lng;

        if($request->u_password)
        {
            $user->password = $request->u_password;
        }
        if($request->u_image)
        {
            $user->image = $request->u_image;
        }
        if($request->u_email)
        {
            if( !(User::whereEmail($request->u_email)->first()) ){
                $user->email = $request->u_email;
            }
        }
        if($request->u_phone)
        {
            if( !(User::wherePhone($request->u_phone)->first()) ){
                $user->phone = $request->u_phone;
            }
        }
        $user->save();

        $userData=User::whereId($request->u_id)->first();
        Session::put('u_id',$userData->id);
        Session::put('u_email',$userData->email);
        Session::put('u_phone',$userData->phone);
        Session::put('u_name',$userData->name);
        Session::put('u_image',$userData->image);
        Session::put('lat',$userData->lat);
        Session::put('lng',$userData->lng);



        return back();
    }

    public function contact_uss(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'body' => 'required',

        ]);
        $add            = new ContactUs();
        $add->name   = $request->name;
        $add->email   = $request->email;
        $add->body   = $request->body;
        $add->save();
        return redirect("/");
    }

    public function send_newsletter(Request $request){
        $this->validate($request,[
            'newsletter' => 'required',

        ]);
        $add            = new Newsletter();
        $add->email   = $request->newsletter;
        $add->save();
        return redirect("/");
    }


}
