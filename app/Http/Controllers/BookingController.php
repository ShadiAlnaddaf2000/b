<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Finance;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /*
       * الحجز
       * */
    public function addBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer',
            'person_number' => 'required|integer',
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

        if ($trip->capacity - $trip->current_number < $request->person_number) {
            return response()->json([
                'message' => 'There are no seats available for this number of people'
            ], 400);
        }
        $cost = $trip->cost * $request->person_number;

        if ($cost > $user->wallet) {
            return response()->json([
                'message' => 'You dont have enough money'
            ], 400);
        }
        if (Carbon::parse($trip->starting_date) < now()) {
            return response()->json([
                'message' => 'Your reservation time has expired'
            ], 400);
        }


        $booking = Booking::create([
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'person_number' => $request->person_number,
            'total_price' => $cost,

        ]);

        /*
        الدفع
        */
        $before = $user->wallet;
        $user->decrement('wallet', $cost);
        $admin = User::find(1);
        $admin->increment('wallet', $cost);


        Finance::create([
            'user_id' => $user->id,
            'amount' => $cost,
            'before' => $before,
            'after' => $user->wallet,
            'type' => 'decrement',
            'description' => 'for booking with' . ' ' . $trip->name . ' ' . 'trip'
        ]);
        /*
         * العدد المتاح
         * */
        $trip->increment('current_number', $request->person_number);

        return response()->json([

            'code' => '0',
            'message' => 'Your reservation has been completed successfully ',
            'result' => [
                'trip_name' => $trip->name,
                'cost' => $trip->cost,
                'person_number' => $booking->person_number,
                'total_price' => $booking->total_price,
            ]
        ], 201);
    }

    /*
     * حذف الحجز
     * */
    public function deleteBook($id)
    {
        $book = Booking::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }

        if (Carbon::parse($book->trip->starting_date) < now()) {
            return response()->json([
                'message' => 'time has expired'
            ], 400);
        }

        /*
                الدفع
        */
        $before = $book->user->wallet;
        $book->user->increment('wallet', $book->total_price);
        $admin = User::find(1);
        $admin->decrement('wallet', $book->total_price);

        Finance::create([
            'user_id' => $book->user->id,
            'amount' => $book->total_price,
            'before' => $before,
            'after' => $book->user->wallet,
            'type' => 'increment',
            'description' => 'for cancel booking with' . ' ' . $book->trip->name . ' ' . 'trip'
        ]);
        /*
         * العدد المتاح
         * */
        $book->trip->decrement('current_number', $book->person_number);


        $book->delete();
        return response()->json([
            'message' => 'Booking deleted successfully ',
        ], 201);
    }


    /*
           * تعديل الحجز
           * */

    public function updateBook(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'person_number' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        $cost = $booking->trip->cost * $booking->person_number;
        $cost1 = $booking->trip->cost * $request->person_number;

        $before = $booking->user->wallet;
        $booking->user->increment('wallet', $cost);
        $admin = User::find(1);
        $admin->decrement('wallet', $cost);


        Finance::create([
            'user_id' => $booking->user->id,
            'amount' => $cost,
            'before' => $before,
            'after' => $booking->user->wallet,
            'type' => 'increment',
            'description' => 'for update booking with' . ' ' . $booking->trip->name . ' ' . 'trip'
        ]);
        /*
         * العدد المتاح
         * */
        $booking->trip->decrement('current_number', $booking->person_number);

        if ($booking->trip->capacity - $booking->trip->current_number < $request->person_number) {
            return response()->json([
                'message' => 'There are no seats available for this number of people'
            ], 400);
        }

        if ($cost1 > $booking->user->wallet) {
            return response()->json([
                'message' => 'You dont have enough money'
            ], 400);
        }
        if (Carbon::parse($booking->trip->starting_date) < now()) {
            return response()->json([
                'message' => 'Your reservation time has expired'
            ], 400);
        }


        $booking->update([
            'person_number' => $request->person_number,
            'total_price' => $cost1,

        ]);

        /*
        الدفع
        */
        $before1 = $booking->user->wallet;
        $booking->user->decrement('wallet', $cost1);
        $admin->increment('wallet', $cost1);

        Finance::create([
            'user_id' => $booking->user->id,
            'amount' => $cost1,
            'before' => $before,
            'after' => $booking->user->wallet,
            'type' => 'decrement',
            'description' => 'for update booking with' . ' ' . $booking->trip->name . ' ' . 'trip'
        ]);

        /*
         * العدد المتاح
         * */
        $booking->trip->increment('current_number', $request->person_number);


        return response()->json([

            'code' => '0',
            'message' => 'Your reservation has been completed successfully ',
            'result' => [
                'trip_name' => $booking->trip->name,
                'cost' => $booking->trip->cost,
                'person_number' => $booking->person_number,
                'total_price' => $booking->total_price,
            ]
        ], 201);
    }/*
جلب رحلة*/
    public function getUserBook($id)
    {
        $user = Auth::user();

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'This is booking ',
                'result' => [
                    'id'=>$booking->id,
                    'trip_name' => $booking->trip->name,
                    'cost' => $booking->trip->cost,
                    'person_number' => $booking->person_number,
                    'total_price' => $booking->total_price,
                ]
            ]
            , 201);
    }

    /*
    * جلب حجوزات المستخدم
    * */
    public function getAllUserBookings()
    {
        $user = Auth::user();
        $bookings = [];
        foreach ($user->bookings as $data1) {

            array_push($bookings, [
                'id'=>$data1->id,
                'trip_name' => $data1->trip?->name,
                'cost' => $data1->trip?->cost,
                'person_number' => $data1->person_number,
                'total_price' => $data1->total_price
            ]);
        }

        if (empty($bookings)) {
            return response()->json([
                'message' => 'Not found bookings',
            ], 404);
        }


        return response()->json([
                'message' => 'user trips',
                'result' => [
                    'data' => $bookings
                ]
            ]
            , 201);
    }

    /* جلب جميع الحجوزات*/
    public function getAllBookings()
    {
        $bookings = [];
        $data = Booking::get();
        foreach ($data as $data1) {

            array_push($bookings, [
                'id'=>$data1->id,
                'trip_name' => $data1->trip?->name,
                'cost' => $data1->trip?->cost,
                'person_number' => $data1->person_number,
                'total_price' => $data1->total_price
            ]);
        }

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Bookings not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'All Bookings',
                'result' => [
                    'bookings' => $bookings,
                ]
            ]
            , 201);
    }

    /*
     * جلب سجل اليوزر
     * */
    public function getUserFinance()
    {
        $user = Auth::user();
        $finances = [];
        foreach ($user->finances as $data1) {

            array_push($finances, [
                'id'=>$data1->id,
                'amount' => $data1->amount,
                'before' => $data1->before,
                'after' => $data1->after,
                'type' => $data1->type,
                'description' => $data1->description,
                'date' => $data1->created_at->format('Y-m-d')
            ]);
        }

        if (empty($finances)) {
            return response()->json([
                'message' => 'Not found finances',
            ], 404);
        }


        return response()->json([
                'message' => 'user finances',
                'result' => [
                    'data' => $finances
                ]
            ]
            , 201);
    }

    public function viewUserFinance($id)
    {
        $user = User::find($id);
        $finances = [];
        foreach ($user->finances as $data1) {

            array_push($finances, [
                'id'=>$data1->id,
                'amount' => $data1->amount,
                'before' => $data1->before,
                'after' => $data1->after,
                'type' => $data1->type,
                'description' => $data1->description,
                'date' => $data1->created_at->format('Y-m-d')
            ]);
        }

        if (empty($finances)) {
            return response()->json([
                'message' => 'Not found finances',
            ], 404);
        }


        return response()->json([
                'message' => 'user finances',
                'result' => [
                    'data' => $finances

                ]
            ]
            , 201);
    }

    public function getTripBook($id)
    {
        $trip = Trip::find($id);
        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found',
            ], 404);
        }
        $bookings = [];
        foreach ($trip->bookings as $booking) {
            array_push($bookings, [
                'id'=>$booking->id,
                'trip_name' => $booking->trip?->name,
                'cost' => $booking->trip?->cost,
                'person_number' => $booking->person_number,
                'total_price' => $booking->total_price
            ]);
        }
        if (empty($bookings)) {
            return response()->json([
                'message' => 'Bookings not found',
            ], 404);
        }


        return response()->json([
                'code' => '0',
                'message' => 'This is booking ',
                'result' => [
                    'bookings' => $bookings,

                ]
            ]
            , 201);
    }
}
