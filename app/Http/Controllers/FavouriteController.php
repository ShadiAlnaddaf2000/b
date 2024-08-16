<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavouriteController extends Controller
{
    /*
    ***إضافة إلى المفضلة
    */
    public function addfav(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'trip_id' => 'required|integer'
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

        $user = Auth::user();

        $favourite = Favourite::create([
            'user_id' => $user->id,
            'trip_id' => $trip->id
        ]);

        return response()->json([
            'code'=>'0',
            'message' => 'The trip has been added to your favourite successfully'
        ],201);
    }

    /*
    ***حذف من المفضلة
    */

    public function deletefav($id){

        $fav = Favourite::find($id);
        if (!$fav) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }

        $fav->delete();

        return response()->json([
            'message' => 'Successfully deleted from the favourite'
        ],200);
    }

    /*
    ***جلب الرحلات المفضلة للمستخدم
    */

    public function getfav(){
        $user = Auth::user();
        $favourites = Favourite::where('user_id', $user->id)->with('trip')->get();

        if ($favourites->isEmpty()) {
            return response()->json([
                'message' => 'There are no favourite trips'
            ]);
        }

        $trips = $favourites->map(function ($favourite) {
            return $favourite->trip;
        });

        return response()->json([
            'message' => 'Favourite trips',
            'result' => [
                'data' => $trips
            ]
        ]
        , 200);
    }
}
