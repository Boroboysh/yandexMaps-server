<?php

namespace App\Http\Controllers;

use App\Models\Pointers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getPointList()
    {
        return Pointers::select('*')->where("user_id", Auth::id())->get();
    }

    public function newPoint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between: -180,180'
        ], [
            'between' => 'The :attribute value :input is not between :min - :max.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ]);
        }

        Pointers::create([
            'name' => $request->input('namePoint'),
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
            'user_id' => Auth::id()
        ]);

        // username_id Должен передаваться с фронта...?
        // Передается api ключ, ищется в таблцие users юзер с этим ключом, возвращается id user таким ключом и записывается в username_id

        return Pointers::select('*')->where("user_id", Auth::id())->get();
    }

        public function updatePoint($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between: -180,180'
        ], [
            'between' => 'The :attribute value :input is not between :min - :max.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ]);
        }


        Pointers::where('id', $id)->update([
            'name' => $request->input('name'),
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
            'user_id' => Auth::id()
        ]);

        return Pointers::select('*')->where("user_id", Auth::id())->get();
    }

    public function deletePoint($id)
    {
        Pointers::where('id', $id)->where('user_id', Auth::id())->delete();

        return Pointers::select('*')->where("user_id", Auth::id())->get();
    }
}

