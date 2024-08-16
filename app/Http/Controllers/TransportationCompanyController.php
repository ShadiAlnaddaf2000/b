<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Facility;
use App\Models\TransportationCompany;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransportationCompanyController extends Controller
{
    use PhotoTrait;
    /*
    * اضافة شركة
    * */
    public function addCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer',
            'transport_type' => 'required|string',
            'imgs' => 'required',
            'imgs.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:512'],

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $images = $this->upload($request->imgs);

        $company = TransportationCompany::create([
            'name' => $request->name,
            'price' => $request->price,
            'transport_type' => $request->transport_type,
            'description' => $request->description,
            'imgs' => $images,
        ]);

        return response()->json([
            'code' => '0',
            'message' => 'Company added successfully ',
            'result' => [
                'company_name' => $company->name,
                'price' => $company->price,
                'transport_type' => $company->transport_type,
                'description' => $company->description,
                'imgs' => json_decode($images)

            ]
        ], 201);
    }

    /*
     * تعديل معلومات الشركة
    */

    public function updateCompany(Request $request, $id)
    {
        $company = TransportationCompany::find($id);
        if (!$company) {
            return response()->json([
                'message' => 'Facility not found'
            ], 400);

        }
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'price' => 'integer',
            'transport_type' => 'string',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors()->toJson(), 400);
        }
        $company->update($request->all());


        return response()->json([
                'message' => 'City updated successfully ',
                'result' => [
                    'company_name' => $company->name,
                    'price' => $company->price,
                    'transport_type' => $company->transport_type,
                    'description' => $company->description,
                ]
            ]
            , 201);
    }

    /*
        حذف شركة
        */
    public function deleteCompany($id)
    {
        $company = TransportationCompany::find($id);

        if (!$company) {
            return response()->json([
                'message' => 'Company not found',
            ], 404);
        }


        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully ',
        ], 201);

    }

    /*
    * البحث عن شركة
    * */

    public function searchCompany($name)
    {

        $company = TransportationCompany::where('name', 'like', '%' . $name . '%')->get();
        $companies = [];
        foreach ($company as $data) {

            array_push($companies, [
                'id'=>$data->id,
                'name' => $data->name,
                'description' => $data->description,
                'transport_type' => $data->transport_type,
                'price' => $data->price,
                'imgs'=>json_decode($data->imgs)
            ]);
        }

        if ($company->isEmpty()) {
            return response()->json([
                'message' => 'Company not found',
            ], 404);
        }


        return response()->json([
                'message' => 'Companies as name ',
                'result' => [
                    'data' => $companies,
                ]
            ]
            , 201);
    }

    /*
        عرض الشركة
    */
    public function getCompany($id)
    {
        $company = TransportationCompany::find($id);
        if (!$company) {
            return response()->json([
                'message' => 'Company not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'This is company ',
                'result' => [
                    'id'=>$company->id,
                    'company_name' => $company->name,
                    'description' => $company->description,
                    'transport_type' => $company->transport_type,
                    'price' => $company->price,
                    'imgs'=>json_decode($company->imgs)
                ]
            ]
            , 201);
    }

    /*
  عرض كل الشركات
 */
    public function getAllCompany()
    {
        $companies = [];
        $data = TransportationCompany::get();
        foreach ($data as $data1) {

            array_push($companies, [
                'id'=>$data1->id,
                'name' => $data1->name,
                'description' => $data1->description,
                'transport_type' => $data1->transport_type,
                'price' => $data1->price,
                'imgs'=>json_decode($data1->imgs)
            ]);
        }

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Companies not found',
            ], 404);
        }
        return response()->json([
                'code' => '0',
                'message' => 'All companies',
                'result' => [
                    'facilities' => $companies,
                ]
            ]
            , 201);
    }
    public function updatePhoto(Request $request, $id)
    {
        $company = TransportationCompany::find($id);

        if (!$company) {
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

        $company->update([
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
