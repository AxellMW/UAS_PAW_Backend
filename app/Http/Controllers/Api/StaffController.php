<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Staff;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::all();

        if (count($staffs) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $staffs
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $staff = Staff::find($id);

        if (!is_null($staff)){
            return response([
                'message' => 'Retrieve Staff Success',
                'data' => $staff
            ], 200);
        }

        return response([
            'message' => 'Staff Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'name' => 'required',
            'position' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $staff = Staff::create($storeData);
        return response([
            'message' => 'Add Staff Success',
            'data' => $staff
        ], 200);
    }

    public function destroy($id)
    {
        $staff = Staff::find($id);

        if (is_null($staff)) {
            return response([
                'message' => 'Staff Not Found',
                'data' => null
            ], 404);
        }

        if ($staff->delete()){
            return response([
                'message' => 'Delete Staff Success',
                'data' => $staff
            ], 200);
        }

        return response([
            'message' => 'Delete Staff Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);
        if (is_null($staff)){
            return response([
                'message' => 'Staff Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => ['required'],
            'position' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $staff->name = $updateData['name'];
        $staff->position = $updateData['position'];

        if($staff->save()) {
            return response([
                'message' => 'Update Staff Success',
                'data' => $staff
            ], 200);
        }

        return response([
            'message' => 'Update Staff Failed',
            'data' => null,
        ], 400);
    }
}
