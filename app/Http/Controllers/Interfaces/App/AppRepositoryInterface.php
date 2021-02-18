<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 18/06/2019
 * Time: 03:51 م
 */

namespace App\Http\Controllers\Interfaces\App;


interface AppRepositoryInterface
{
    public function getFeePercent();
    public function countries();
    public function carTpes();
    public function nationalTypes();
    public function countriesCodes();
    public function cities($request);
    public function complainAndSuggestion($attributes);
    public function aboutUs();
    public function termCondition();
    public function contactUs($request);
    public function appExplanation();
    public function getNotifications($request,$user_id, $lang);
}
