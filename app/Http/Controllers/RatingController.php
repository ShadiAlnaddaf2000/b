<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /*
    ***إضافة وتعديل تقييم
    */
    public function updaterate(Request $request) {
        $validator = Validator::make($request->all(),[
            'trip_id' => 'required|integer',
            'rating' => 'required|integer|max:50'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $trip = Trip::find($request->trip_id);
        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 400);
        }

        $user=Auth::user();

        $book=Booking::where('trip_id',$trip->id)->where('user_id',$user->id)->first();
        if (!$book) {
            return response()->json([
                'message' => 'The trip is not booked'
            ],404);
        }


        $book->rating=$request->rating;
        $book->save();

        return response()->json([
            'code' => '0',
            'message' => 'Rate has been adeed successfully'
        ],201);
    }

    public function getrating($id){
        $trip=Trip::find($id);
        if (!$trip) {
            return response()->json([
                'message' => 'trip not found'
            ],404);
        }

        $allratings=Booking::where('trip_id',$id)->get();
        if ($allratings->isEmpty()) {
            return response()->json([
                'code' => '0',
                'message' => 'no rating',
                'result' => 0
            ],200);
        }

        $rate=$allratings->avg('rating');

        return response()->json([
            'code' => '0',
            'result' => $rate
        ],200);
    }

}
