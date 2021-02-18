<?php

namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Interfaces\User\HomeRepositoryInterface;
use App\Models\Admin;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    protected $homeRepository;

    public function __construct(HomeRepositoryInterface $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function categoriesShops(Request $request)
    {
        $catItem0 = [
            "id" => 0,
            "name" => "الكل",
            "image" => asset('uploads/categories/Group9569.png'),
        ];
        $user = checkJWT($request->header('jwt'));
        //dd($user);
        if($user){
            $cats = $this->homeRepository->category($request->header('lang'));
            $shops = $this->homeRepository
                ->shops($request, $request->header('lang'),$user->user_country_id);
            $cats->prepend($catItem0);

            return response()->json(msgdata($request, success(),
                'success', ['categories' => $cats, 'shops' => $shops]));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));

    }

    public function shopsByCategory(Request $request)
    {
        $user = checkJWT($request->header('jwt'));
        $data = $this->homeRepository
            ->shopsByCategory($request, $request->header('lang'),$user->user_country_id);

        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function shopDetails(Request $request)
    {
        $data = $this->homeRepository->shopDetails($request, $request->header('lang'));

        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function shopRates(Request $request)
    {
        $data = $this->homeRepository->shopRates($request, $request->header('lang'));

        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function productDetails(Request $request)
    {
        $data = $this->homeRepository->productDetails($request, $request->header('lang'));

        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function rateShop(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->rateShop($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getRates(Request $request)
    {
        $data = $this->homeRepository->getRates($request, $request->header('lang'));

        return response()->json(msgdata($request, success(), 'success', $data));
    }

    public function homeThirdDepartment(Request $request)
    {
        $data = $this->homeRepository->homeThirdDepartment($request, $request->header('lang'));

        return response()->json(msgdata($request, success(), 'success', $data));
    }
    //////////////////////////////////////////////////////////
    ///
    public function makeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products[id]' => 'sometimes|exists:products,id',
            'products[variations][id]' => 'sometimes|exists:variations,id',
            'products[variations][options][id]' => 'sometimes|exists:options,id',
            //'shop_id' => 'sometimes|exists:shops,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => failed(), 'msg' => $validator->messages()->first()]);
        }

        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->makeOrder($request, $user->id, $request->header('lang'),$user->user_country_id);

            return response()->json(msgdata($request, success(), 'success',$data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function reOrder(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->reOrder($request, $user->id, $request->header('lang'),$user->user_country_id);

            return response()->json(msgdata($request, success(), 'success',$data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function acceptOffer(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->acceptOffer($request, $user->id, $request->header('lang'));

            return response()->json(msg($request, success(), 'success'));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function cancelOrder(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->cancelOrder($request, $user->id, $request->header('lang'));

            return response()->json(msg($request, success(), 'success'));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function acceptConfirmRequest(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->acceptConfirmRequest($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success',$data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getOrdersOffers(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->getOrdersOffers($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getOffers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->messages()->first()]);
        }
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->getOffers($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getDelegateOrdersRates(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->getDelegateOrdersRates($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getLowerOffer(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->getLowerOffer($request, $user->id, $request->header('lang'));
            if ($data)
                return response()->json(msg($request, success(), 'success'));
            return response()->json(msg($request, failed(), 'max_offers_limit'));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function changeMyDelegate(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->changeMyDelegate($request, $user->id, $request->header('lang'));
            if ($data){
                if($data == "cant_change_delegate")
                    return response()->json(msg($request, failed(), 'cant_change_delegate'));
                if($data == "delegate_changed")
                    return response()->json(msg($request, success(), 'success'));
            }

            return response()->json(msg($request, failed(), 'max_offers_limit'));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function checkPromo(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->checkPromo($request, $user->country_id, $request->header('lang'));

            if($data == 'invalid_code' OR  $data == 'invalid_code_')
                return response()->json(msg($request, failed(), 'invalid_code'));
            if($data == 'code_expired')
                return response()->json(msg($request, failed(), 'invalid_code'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function savedLocations(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->savedLocations($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getNotifications(Request $request)
    {
        //0=>user, 1=>delegate, 2=>driver
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->getNotifications($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getHistoryOrders(Request $request)
    {
        //0=>user, 1=>delegate, 2=>driver
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->getHistoryOrders($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getReplacedPoints(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->getReplacedPoints($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getofferPoints(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->getofferPoints($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function replacePoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_point_id' => 'required|exists:offer_points,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->messages()->first()]);
        }
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository->replacePoints($request, $user->id, $request->header('lang'));

            if ($data)
                return response()->json(msg($request, success(), 'points_replaced_success'));
            return response()->json(msg($request, failed(), 'not_enough_points'));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getUserWalletRecharges(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->getUserWalletRecharges($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function raiseUserWallet(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->raiseUserWallet($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getUserAdminMessages(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->getUserAdminMessages($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function chatWithAdmin(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->chatWithAdmin($request, $user->id, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getUserMessages(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->getUserMessages($request, $user->id, $user->image, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function sendMessage(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->sendMessage($request, $user->id, $user->image, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function shopSliders(Request $request)
    {
        $fee = Admin::where('email',"admin@admin.com")->select('fee_percent')->first()->fee_percent;
        $sliders = $this->homeRepository
            ->shopSliders($request, $request->header('lang'));
        $departments = $this->homeRepository
            ->departments($request, $request->header('lang'));

        return response()->json(msgdata($request, success(), 'success',
            ['fee' => $fee,'sliders' => $sliders, 'departments' => $departments]));
    }

    public function searchShops(Request $request)
    {
        if ($user = checkJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->searchShops($request, $user->id, $request->header('lang'),$user->user_country_id);

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function getOrderStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 401, 'msg' => $validator->messages()->first()]);
        }
        if ( checkJWT($request->header('jwt')) OR  checkDelegateJWT($request->header('jwt'))) {
            $data = $this->homeRepository
                ->getOrderStatus($request, $request->header('lang'));

            return response()->json(msgdata($request, success(), 'success', $data));
        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

    public function rateOrder(Request $request)
    {
        if ($user = checkJWT($request->header('jwt')) OR $delegate = checkDelegateJWT($request->header('jwt'))) {
            if($user)
                $user_type =0;
            elseif($delegate)
                $user_type =1;
            //
            $data = $this->homeRepository
                ->rateOrder($request, $request->header('lang') , $user_type);

                return response()->json(msg($request, success(), 'success'));

        }
        return response()->json(msg($request, not_authoize(), 'invalid_data'));
    }

}
