<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Facility;
use App\Models\Hotel;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    use PhotoTrait;
    /*
    * اضافة فندق
    * */
    public function addHotel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer',
            'city_id' => 'required|integer',
            'imgs' => 'required',
            'imgs.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:512'],
        ]);
        $city = City::find($request->city_id);
        if (!$city) {
            return response()->json([
                'message' => 'city not found'
            ], 400);

        }
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $images = $this->upload($request->imgs);

        $hotel = Hotel::create([
                'name' => $request->name,
                'description' => $request->description,
                'city_id'=>$request->city_id,
                'price'=>$request->price,
                'imgs' => $images,
            ]
        );

        return response()->json([
            'code' => '0',
            'message' => 'Hotel added successfully ',
            'result' => [
                'hotel_name' => $hotel->name,
                'description' => $hotel->description,
                'price' => $hotel->price,
                'city_name' => $hotel->city->name,
                'country_name' => $hotel->city->country->name,
                'imgs'=>json_decode($images)

            ]
        ], 201);
    }

    /*
     * تعديل معلومات الفندق
    */

    public function updateHotel(Request $request, $id)
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return response()->json([
                'message' => 'Hotel not found'
            ], 400);

        }
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'price' => 'integer',
            'city_id' => 'integer',
        ]);
        $city = City::find($request->city_id);
        if (!$city) {
            return response()->json([
                'message' => 'city not found'
            ], 400);
        }


        if ($validator->fails()) {

            return response()->json($validator->errors()->toJson(), 400);
        }
        $hotel->update($request->all());


        return response()->json([
                'message' => 'Hotel updated successfully ',
                'result' => [
                    'hotel_name' => $hotel->name,
                    'description' => $hotel->description,
                    'price' => $hotel->price,
                    'city_name' => $hotel->city->name,
                    'country_name' => $hotel->city->country->name,

                ]
            ]
            , 201);
    }

    /*
    حذف فندق
    */
    public function deleteHotel($id)
    {
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return response()->json([
                'message' => 'Hotel not found',
            ], 404);
        }

        $hotel->delete();

        return response()->json([
            'message' => 'Hotel deleted successfully ',
        ], 201);

    }

    /*
     * البحث عن فندق*/
    public function searchHotel($name)
    {

        $hotel = Hotel::where('name', 'like', '%' . $name . '%')->get();
        $hotels = [];
        foreach ($hotel as $data) {

            array_push($hotels, [
                'id'=>$data->id,
                'name' => $data->name,
                'description' => $data->description,
                'city_name' => $data->city->name,
                'country_name' => $data->city->country->name,
                'imgs'=>json_decode($data->imgs)
            ]);
        }

        if ($hotel->isEmpty()) {
            return response()->json([
                'message' => 'Hotel not found',
            ], 404);
        }


        return response()->json([
                'message' => 'Hotel as name ',
                'result' => [
                    'data' => $hotels,
                ]
            ]
            , 201);
    }

    /*
     عرض الفندق
 */
    public function getHotel($id)
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return response()->json([
                'message' => 'Hotel not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'This is Hotel ',
                'result' => [
                    'id'=>$hotel->id,
                    'hotel_name' => $hotel->name,
                    'description' => $hotel->description,
                    'city_name' => $hotel->city->name,
                    'country_name' => $hotel->city->country->name,
                    'imgs'=>json_decode($hotel->imgs)
                ]
            ]
            , 201);
    }

    /*
   عرض كل الفنادق
  */
    public function getAllHotel()
    {
        $hotels = [];
        $data = Hotel::get();
        foreach ($data as $data1) {

            array_push($hotels, [
                'id'=>$data1->id,
                'name' => $data1->name,
                'description' => $data1->description,
                'city_name' => $data1->city->name,
                'country_name' => $data1->city->country->name,
                'imgs'=>json_decode($data1->imgs)
            ]);
        }

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Hotel not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'All hotels',
                'result' => [
                    'hotels' => $hotels,
                ]
            ]
            , 201);
    }

    /*
 * جلب الفنادق حسب المدينة
 * */
    public function getHotelByCity($id)
    {
        $city = City::find($id);
        if (!$city) {
            return response()->json([
                'message' => 'city not found'
            ], 400);
        }

        $hotel = Hotel::where('city_id', $id)->get();
        $hotels = [];
        foreach ($hotel as $data) {

            array_push($hotels, [
                'id'=>$data->id,
                'name' => $data->name,
                'description' => $data->description,
                'city_name' => $data->city->name,
                'country_name' => $data->city->country->name,
                'imgs'=>json_decode($data->imgs)
            ]);
        }

        if ($hotel->isEmpty()) {
            return response()->json([
                'message' => 'Hotel not found',
            ], 404);
        }


        return response()->json([
                'message' => 'Hotels as city ',
                'result' => [
                    'data' => $hotels,
                ]
            ]
            , 201);
    }
    public function updatePhoto(Request $request, $id)
    {
        $hotel = Hotel::find($id);

        if (!$hotel) {
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

        $hotel->update([
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
