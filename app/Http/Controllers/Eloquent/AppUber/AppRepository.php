<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 18/06/2019
 * Time: 03:52 م
 */

namespace App\Http\Controllers\Eloquent\AppUber;


use App\Http\Controllers\Interfaces\AppUber\AppRepositoryInterface;
use App\Models\AboutUs;
use App\Models\ComplainSuggests;
use App\Models\CriedtCard;
use App\Models\Notification;
use App\Models\Term;
use App\Models\Issue;
use App\Models\Lost;
use App\Models\User;

class AppRepository implements AppRepositoryInterface
{
    public function complainAndSuggestion($input,$user_id,$type)
    {
        $array = array(
            'title' => isset($input->title) ? $input->title : "",
            'description' => isset($input->description) ? $input->description : "",
            'issue_id' => isset($input->issue_id) ? $input->issue_id : null,
            'lost_id' => isset($input->lost_id) ? $input->lost_id : null,
            'user_id' => $user_id,
            'trip_id' => isset($input->trip_id) ? $input->trip_id : null,
            'type' => $type,
        );
        ComplainSuggests::create($array);
        return true;
    }

    public function aboutUs()
    {
        return new AboutUs;
    }

    public function termCondition()
    {
        return new Term();
    }

    public function issues($input,$lang)
    {
        return Issue::where("is_captin",$input->is_captin)
            ->select('id',$lang.'_issue as issue')->get();
    }

    public function losts($input,$lang)
    {
        return Lost::select('id',$lang.'_lost as lost')->get();
    }

    public function getCrieditCards($user_id)
    {
        return CriedtCard::where('user_id',$user_id)->get();
    }

    public function addCrieditCard($input,$user_id)
    {
        CriedtCard::where('user_id',$user_id)->update(['active' => 0]);
        $array = array(
            'card_num' => $input->card_num,
            'expire_date' => $input->expire_date,
            'cvv' => $input->cvv,
            'name' => $input->cvv,
            'active' => 1,
            'user_id' => $user_id,
        );
        CriedtCard::create($array);
        return CriedtCard::where('user_id',$user_id)->get();
    }

    public function activateCrieditCard($input,$user_id)
    {
        CriedtCard::where('user_id',$user_id)->update(['active' => 0]);

        CriedtCard::whereId($input->criedit_card_id)
            ->where('user_id',$user_id)->update(['active' => 1]);
    }

    public function walletchangeStatus($user_id)
    {
        $wallet_flag=User::whereId($user_id)->first()->wallet_flag;
        if($wallet_flag == 1){
            User::whereId($user_id)->update(['wallet_flag' => 0]);
        }else{
            User::whereId($user_id)->update(['wallet_flag' => 1]);
        }
    }

    public function notifications($user_id,$lang)
    {
        return  Notification::orderBy('id','desc')
            ->select("id","title","body","created_at")
            ->where("user_id",$user_id)
            ->get();

    }
}
