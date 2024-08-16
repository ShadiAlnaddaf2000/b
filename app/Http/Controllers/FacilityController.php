<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Facility;
use App\Models\Hotel;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    use PhotoTrait;
    /*
    * اضافة منشأة
    * */
    public function addFacility(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'city_id' => 'required|integer',
            'imgs'=> 'required',
            'imgs.*' => [ 'image', 'mimes:jpeg,png,jpg,gif', 'max:512'],
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

        $facility = Facility::create([
                'name' => $request->name,
                'description' => $request->description,
                'city_id'=>$request->city_id,
                'imgs' => $images,
          ]
        );

        return response()->json([
            'code' => '0',
            'message' => 'Facility added successfully ',
            'result' => [
                'facility_name' => $facility->name,
                'city_name' => $facility->city->name,
                'country_name' => $facility->city->country->name,
                'description' => $facility->description,
                'imgs'=>json_decode($images),

            ]
        ], 201);
    }

    /*
     * تعديل معلومات المنشأة
    */

    public function updateFacility(Request $request, $id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return response()->json([
                'message' => 'Facility not found'
            ], 400);

        }
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'city_id' => 'integer'
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
        $facility->update($request->all());


        return response()->json([
                'message' => 'City updated successfully ',
                'result' => [
                    'name' => $facility->name,
                    'city_name' => $facility->city->name,
                    'country_name' => $facility->city->country->name,
                    'description' => $facility->description,


                ]
            ]
            , 201);
    }

    /*
    حذف منشأة
    */
    public function deleteFacility($id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json([
                'message' => 'Facility not found',
            ], 404);
        }


        $facility->delete();

        return response()->json([
            'message' => 'Facility deleted successfully ',
        ], 201);

    }

    /*
     * البحث عن المنشأة
     * */

    public function searchFacility($name)
    {

        $facility = Facility::where('name', 'like', '%' . $name . '%')->get();
        $facilities = [];
        foreach ($facility as $data) {

            array_push($facilities, [
                'id'=>$data->id,
                'name' => $data->name,
                'description' => $data->description,
                'city_name' => $data->city->name,
                'country_name' => $data->city->country->name,
                'imgs'=>json_decode($data->imgs)

            ]);
        }

        if ($facility->isEmpty()) {
            return response()->json([
                'message' => 'Facility not found',
            ], 404);
        }


        return response()->json([
                'message' => 'Facility as name ',
                'result' => [
                    'data' => $facilities,
                ]
            ]
            , 201);
    }

    /*
         عرض المنشأة
     */
    public function getFacility($id)
    {
        $facility = Facility::find($id);
        if (!$facility) {
            return response()->json([
                'message' => 'Facility not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'This is Facility ',
                'result' => [
                    'id'=>$facility->id,
                    'facility_name' => $facility->name,
                    'description' => $facility->description,
                    'city_name' => $facility->city->name,
                    'country_name' => $facility->city->country->name,
                    'imgs'=>json_decode($facility->imgs)
                ]
            ]
            , 201);
    }
    /*
   عرض كل المنشات
  */
    public function getAllFacility()
    {
        $facilities = [];
        $data = Facility::get();
        foreach ($data as $data1) {

            array_push($facilities, [
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
                'message' => 'facilities not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'All facilities',
                'result' => [
                    'facilities' => $facilities,
                ]
            ]
            , 201);
    }


    /*
     * جلب المنشات حسب المدينة
     * */
    public function getFacilityByCity($id)
    {
        $city = City::find($id);
        if (!$city) {
            return response()->json([
                'message' => 'city not found'
            ], 400);
        }

        $facility = Facility::where('city_id', $id)->get();
        $facilities = [];
        foreach ($facility as $data) {

            array_push($facilities, [
                'id'=>$data->id,
                'name' => $data->name,
                'description' => $data->description,
                'city_name' => $data->city->name,
                'country_name' => $data->city->country->name,
                'imgs'=>json_decode($data->imgs)

            ]);
        }

        if ($facility->isEmpty()) {
            return response()->json([
                'message' => 'Facility not found',
            ], 404);
        }


        return response()->json([
                'message' => 'Facilities as city ',
                'result' => [
                    'data' => $facilities
                ]
            ]
            , 201);
    }
    public function updatePhoto(Request $request, $id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json([
                'message' => 'Facility not found',
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

        $facility->update([
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
