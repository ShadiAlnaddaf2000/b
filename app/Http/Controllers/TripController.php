<?php

namespace App\Http\Controllers;


use App\Models\Trip;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    use PhotoTrait;

    /*
    * اضافة رحلة
    * */
    public function addTrip(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|integer',
            'capacity' => 'required|integer',
            'starting_date' => 'required|date',
            'ending_date' => 'required|date',
            'imgs' => 'required',
            'imgs.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:512'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $images = $this->upload($request->imgs);

        $trip = Trip::create([
            'name' => $request->name,
            'description' => $request->description,
            'cost' => $request->cost,
            'capacity'=>$request->capacity,
            'starting_date' => $request->starting_date,
            'ending_date' => $request->ending_date,
            'user_id' => $user->id,
            'imgs' => $images,
        ]);

        return response()->json([
            'code' => '0',
            'message' => 'Trip  added successfully ',
            'result' => [
                'trip_name' => $trip->name,
                'description' => $trip->description,
                'cost' => $trip->cost,
                'capacity'=>$trip->capacity,
                'starting_date' => $trip->starting_date,
                'ending_date' => $trip->ending_date,
                'user_name' => $trip->user->first_name . ' ' . $trip->user->last_name,
                'current_number'=>$trip->current_number,
                'imgs' => json_decode($images)


            ]
        ], 201);
    }

    /*
    * تعديل معلومات الرحلة
   */

    public function updateTrip(Request $request, $id)
    {
        $user = Auth::user();
        $trip = Trip::find($id);
        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 400);

        }
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'cost' => 'integer',
            //'type' => 'required|string',
            'starting_date' => 'date',
            'ending_date' => 'date',

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors()->toJson(), 400);
        }
        $trip->update($request->all());


        return response()->json([
                'message' => 'Trip updated successfully ',
                'result' => [
                    'trip_name' => $trip->name,
                    'description' => $trip->description,
                    'cost' => $trip->cost,
                    'starting_date' => $trip->starting_date,
                    'ending_date' => $trip->ending_date,
                    'user_name' => $trip->user->first_name . ' ' . $trip->user->last_name,
                    'current_number'=>$trip->current_number,

                ]
            ]
            , 201);
    }

    /*
   حذف رحلة
   */
    public function deleteTrip($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found',
            ], 404);
        }


        $trip->delete();

        return response()->json([
            'message' => 'Trip deleted successfully ',
        ], 201);

    }

    /*
     * عرض الرحلة
     * */
    public function getTrip($id)
    {

        $trip = Trip::find($id);
        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'This is Trip ',
                'result' => [
                    'id'=>$trip->id,
                    'trip_name' => $trip->name,
                    'description' => $trip->description,
                    'cost' => $trip->cost,
                    'starting_date' => $trip->starting_date,
                    'ending_date' => $trip->ending_date,
                    'user_name' => $trip->user->first_name . ' ' . $trip->user->last_name,
                    'current_number'=>$trip->current_number,
                    'imgs' => json_decode($trip->imgs)
                ]
            ]
            , 201);
    }

    /*
  عرض كل الرحلات
 */
    public function getAllTrips()
    {
        $trips = [];
        $data = Trip::get();
        foreach ($data as $data1) {

            array_push($trips, [
                'id'=>$data1->id,
                'trip_name' => $data1->name,
                'capacity' => $data1->capacity,
                'description' => $data1->description,
                'cost' => $data1->cost,
                'starting_date' => $data1->starting_date,
                'ending_date' => $data1->ending_date,
                'user_name' => $data1->user->first_name . ' ' . $data1->user->last_name,
                'current_number'=>$data1->current_number,
                'imgs' => json_decode($data1->imgs)
            ]);
        }

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Trips not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'All trips',
                'result' => [
                    'trips' => $trips,
                ]
            ]
            , 201);
    }

    public function updatePhoto(Request $request, $id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'imgs' => 'required',
            'imgs.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:512'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $images = $this->upload($request->imgs);

        $trip->update([
            'imgs' => $images
        ]);
        return response()->json([
                'code' => '0',
                'message' => 'Updated photo',
                'result' => [
                    'imgs' => json_decode($images),
                ]
            ]
            , 201);
    }
/*
*
جلب رحلات الادمن
 */
    public function getAdminTrips()
    {
            $trip = Trip::where('user_id', 1)->get();
            $trips = [];
        foreach ($trip as $data1) {

            array_push($trips, [
                'id'=>$data1->id,
                'trip_name' => $data1->name,
                'description' => $data1->description,
                'cost' => $data1->cost,
                'starting_date' => $data1->starting_date,
                'ending_date' => $data1->ending_date,
                'user_name' => $data1->user->first_name . ' ' . $data1->user->last_name,
                'current_number'=>$data1->current_number,
                'imgs' => json_decode($data1->imgs)

            ]);
        }

        if ($trip->isEmpty()) {
            return response()->json([
                'message' => 'Not found trips',
            ], 404);
        }


        return response()->json([
                'message' => 'Admin trips',
                'result' => [
                    'data' => $trips
                ]
            ]
            , 201);
    }
    /*
     * جلب رحلات المستخدم
     * */
    public function getUserTrips()
    {
        $user = Auth::user();
        $trip = Trip::where('user_id', $user->id)->get();
            $trips = [];
        foreach ($trip as $data1) {

            array_push($trips, [
                'id'=>$data1->id,
                'trip_name' => $data1->name,
                'description' => $data1->description,
                'cost' => $data1->cost,
                'starting_date' => $data1->starting_date,
                'ending_date' => $data1->ending_date,
                'user_name' => $data1->user->first_name . ' ' . $data1->user->last_name,
                'current_number'=>$data1->current_number,
                'imgs' => json_decode($data1->imgs)

            ]);
        }

        if ($trip->isEmpty()) {
            return response()->json([
                'message' => 'Not found trips',
            ], 404);
        }


        return response()->json([
                'message' => 'user trips',
                'result' => [
                    'data' => $trips
                ]
            ]
            , 201);
    }

}
