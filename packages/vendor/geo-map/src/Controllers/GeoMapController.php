<?php 

namespace Vendor\GeoMap\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Vendor\GeoMap\Resources\PlaceResource;
use Illuminate\Http\Request;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Province;
use App\Models\CountryMedia;

class GeoMapController extends Controller
{
    public function getContinent()
    {
        // Logique pour récupérer les continents
    }

    public function getCountrie($countrieCode)
    {
        $countrie = Country::where('code', strtolower($countrieCode))->first();
        $geo_data = Place::where('country_id', $countrie->id)->get();
        $medias = CountryMedia::where('country_id', $countrie->id)->get();
        if (!$countrie) {
            return abort(404, 'Country not found');
        }
        $provinces = $countrie->provinces;
        return view('geo-map::countries.index', compact('countrie', 'provinces','geo_data','medias'));
    }

    
    public function getCountries($countrieCode)
    {
        $countrie = Country::where('code', strtolower($countrieCode))->first();
        if (!$countrie) {
            return abort(404, 'Country not found');
        }
        $provinces = $countrie->provinces;
        return view('geo-map::countries.index1', compact('countrie', 'provinces'));
    }

    public function getProvince($countrieCode, $provinceCode)
    {
        $province = Province::with('country')->where('code', $provinceCode)->first();
        if (!$province) {
            return abort(404, 'Province not found');
        }

        return view('geo-map::provinces.index', compact('province'));
    }
}