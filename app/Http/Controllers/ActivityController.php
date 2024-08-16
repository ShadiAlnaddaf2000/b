<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Facility;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\TransportationCompany;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /*
       * اضافة نشاط
       * */
    public function addActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer',
            'restaurant_id' => 'required|integer',
            'hotel_id' => 'required|integer',
            'transport_id' => 'required|integer',
            'facility_id' => 'required|integer',
        ]);
        $trip = Trip::find($request->trip_id);
        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 400);
        }
        $restaurant = Restaurant::find($request->restaurant_id);
        if (!$restaurant) {
            return response()->json([
                'message' => 'Restaurant not found'
            ], 400);
        }
        $hotel = Hotel::find($request->hotel_id);
        if (!$hotel) {
            return response()->json([
                'message' => 'hotel not found'
            ], 400);
        }
        $transport = TransportationCompany::find($request->transport_id);
        if (!$transport) {
            return response()->json([
                'message' => 'Transportation company not found'
            ], 400);
        }
        $facility = Facility::find($request->facility_id);
        if (!$facility) {
            return response()->json([
                'message' => 'Facility not found'
            ], 400);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $activity = Activity::create([
            'trip_id' => $request->trip_id,
            'restaurant_id' => $request->restaurant_id,
            'hotel_id' => $request->hotel_id,
            'transport_id'=>$request->transport_id,
            'facility_id' => $request->facility_id,

        ]);

        return response()->json([
            'code' => '0',
            'message' => 'Activity  added successfully ',
            'result' => [
                'trip_id' => $activity->trip->name,
                'restaurant_id' => $activity->restaurant->name,
                'hotel_id' => $activity->hotel->name,
                'transport_id'=>$activity->transport->name,
                'facility_id' => $activity->facility->name,


            ]
        ], 201);
    }
    /*
        * تعديل معلومات النشاط
       */

    public function updateActivity(Request $request, $id): JsonResponse
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found'
            ], 400);

        }
        $validator = Validator::make($request->all(), [
            'trip_id' => 'integer',
            'restaurant_id' => 'integer',
            'hotel_id' => 'integer',
            'transport_id' => 'integer',
            'facility_id' => 'integer',

        ]);
        $trip = Trip::find($request->trip_id);
        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 400);
        }
        $restaurant = Restaurant::find($request->restaurant_id);
        if (!$restaurant) {
            return response()->json([
                'message' => 'Restaurant not found'
            ], 400);
        }
        $hotel = Hotel::find($request->hotel_id);
        if (!$hotel) {
            return response()->json([
                'message' => 'hotel not found'
            ], 400);
        }
        $transport = TransportationCompany::find($request->transport_id);
        if (!$transport) {
            return response()->json([
                'message' => 'Transportation company not found'
            ], 400);
        }
        $facility = Facility::find($request->facility_id);
        if (!$facility) {
            return response()->json([
                'message' => 'Facility not found'
            ], 400);
        }


        if ($validator->fails()) {

            return response()->json($validator->errors()->toJson(), 400);
        }


        $activity->update($request->all());


        return response()->json([
                'message' => 'Activity updated successfully ',
                'result' => [
                    'trip_id' => $activity->trip->name,
                    'restaurant_id' => $activity->restaurant->name,
                    'hotel_id' => $activity->hotel->name,
                    'transport_id'=>$activity->transport->name,
                    'facility_id' => $activity->facility->name,

                ]
            ]
            , 201);
    }
    /*
    حذف النشاط
    */
    public function deleteActivity($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found',
            ], 404);
        }


        $activity->delete();

        return response()->json([
            'message' => 'Activity deleted successfully ',
        ], 201);

    }
    /*
       عرض النشاط
    */
    public function getActivity($id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'This is activity ',
                'result' => [
                    'id'=>$activity->id,
                    'trip_name' => $activity->trip->name,
                    'restaurant_name' => $activity->restaurant->name,
                    'hotel_name' => $activity->hotel->name,
                    'transport_name'=>$activity->transport->name,
                    'facility_name' => $activity->facility->name,
                ]
            ]
            , 201);
    }
    public function getTripActivity($id)
    {

        $activity = Activity::where('trip_id', $id)->first();;
        if (!$activity) {
            return response()->json([
                'message' => 'There is no activity for this trip',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'Activity of this trip ',
                'result' => [
                    'id'=>$activity->id,
                    'trip_name' => $activity->trip->name,
                    'restaurant_name' => $activity->restaurant->name,
                    'hotel_name' => $activity->hotel->name,
                    'transport_name'=>$activity->transport->name,
                    'facility_name' => $activity->facility->name,
                ]
            ]
            , 201);
    }

}
