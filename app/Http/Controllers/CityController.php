<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    use PhotoTrait;

    /*
     * اضافة مدينة
     * */
    public function addCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:cities',
            'description' => 'required|string',
            'country_id' => 'required|integer',
            'imgs'=> 'required',
            'imgs.*' => [ 'image', 'mimes:jpeg,png,jpg,gif', 'max:512'],
        ]);
        $country = Country::find($request->country_id);
        if (!$country) {
            return response()->json([
                'message' => 'Country not found'
            ], 400);

        }
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $images = $this->upload($request->imgs);

        $city = City::create([
                'name' => $request->name,
                'description' => $request->description,
                'country_id'=>$request->country_id,
                'imgs' => $images,
            ]
        );


        return response()->json([
            'code' => '0',
            'message' => 'City added successfully ',
            'result' => [
                'city_name' => $city->name,
                'country_name' => $city->country->name,
                'description' => $city->description,
                'imgs'=>json_decode($images),

            ]
        ], 201);
    }

    /*
     * تعديل معلومات المدينة
    */

    public function updateCity(Request $request, $id)
    {
        $city = City::find($id);
        if (!$city) {
            return response()->json([
                'message' => 'city not found'
            ], 400);

        }
        $validator = Validator::make($request->all(), [
            'name' => 'string|unique:cities',
            'description' => 'string',
            'country_id' => 'integer'

        ]);
        $country = Country::find($request->country_id);
        if (!$country) {
            return response()->json([
                'message' => 'Country not found'
            ], 400);
        }


        if ($validator->fails()) {

            return response()->json($validator->errors()->toJson(), 400);
        }


        $city->update($request->all());


        return response()->json([
                'message' => 'City updated successfully ',
                'result' => [
                    'city_name' => $city->name,
                    'country_name' => $city->country->name,
                    'description' => $city->description,

                ]
            ]
            , 201);
    }

    /*
     حذف مدينة
     */
    public function deleteCity($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'message' => 'City not found',
            ], 404);
        }


        $city->delete();

        return response()->json([
            'message' => 'City deleted successfully ',
        ], 201);

    }

    /*
    البحث حسب اسم المدينة
    */
    public function searchCity($name)
    {

        $city = City::where('name', 'like', '%' . $name . '%')->with('country')->get();
        $cities = [];
        //$get = City::with('country')->first();


        foreach ($city as $data) {

            array_push($cities, [
                'id'=>$data->id,
                'name' => $data->name,
                'description' => $data->description,
                'country_name' => $data->country->name,
                'imgs'=>json_decode($data->imgs)

            ]);
        }

        if ($city->isEmpty()) {
            return response()->json([
                'message' => 'City not found',
            ], 404);
        }
        $get = City::with('country')->get();


        return response()->json([
                'message' => 'City as name ',
                'result' => [
                    'data' => $cities,
                ]
            ]
            , 201);
    }

    /*
        عرض المدينة
     */
    public function getCity($id)
    {
        $city = City::find($id);
//        $get = City::with('country')->first();

        if (!$city) {
            return response()->json([
                'message' => 'City not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'This is city ',
                'result' => [
                    'id'=>$city->id,
                    'city_name' => $city->name,
                    'description' => $city->description,
                    'country_name' => $city->country->name,
                    'imgs'=>json_decode($city->imgs)
                ]
            ]
            , 201);
    }

    /*
    عرض كل المدن
   */
    public function getAllCities()
    {
        $cities = [];
        $data = City::with('country')->get();
        foreach ($data as $data1) {

            array_push($cities, [
                'id'=>$data1->id,
                'name' => $data1->name,
                'description' => $data1->description,
                'country_name' => $data1->country->name,
                'imgs'=>json_decode($data1->imgs)
            ]);
        }

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Cities not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'All cities',
                'result' => [
                    'country' => $cities,
                ]
            ]
            , 201);
    }
    public function updatePhoto(Request $request, $id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'message' => 'Country not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'imgs'=> 'required',
            'imgs.*' => [ 'image', 'mimes:jpeg,png,jpg,gif', 'max:512'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $images = $this->upload($request->imgs);

        $city->update([
            'imgs'=>$images
        ]);
        return response()->json([
                'code' => '0',
                'message' => 'Updated photo',
                'result' => [
                    'imgs' =>json_decode($images) ,
                ]
            ]
            , 201);
    }
}
