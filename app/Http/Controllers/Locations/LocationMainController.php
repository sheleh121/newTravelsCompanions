<?php
/**
 * Created by PhpStorm.
 * User: s112
 * Date: 01.11.2018
 * Time: 18:07
 */

namespace App\Http\Controllers\Locations;
use App\Http\Controllers\Controller;

use App\LocationCountry;
use App\LocationRegion;
use App\LocationCity;
use Illuminate\Http\Request;

class LocationMainController extends Controller
{
    public function GetCountry(){
        return LocationCountry::select('id','name')->get()->sortBy('name');
    }

    public function GetRegion(Request $request){
        return LocationRegion::all()->where('country_id', $request['country'])->sortBy('name');
    }

    public function GetCities(Request $request){
        return LocationCity::all()->where('region_id', $request['region'])->sortBy('name');
    }

}
