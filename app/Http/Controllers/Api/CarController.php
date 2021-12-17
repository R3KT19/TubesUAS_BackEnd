<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();

        if(count($cars) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $cars
            ], 200);
        }
        else
        {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
    }

    public function show($id)
    {
        $car = Car::where('usersID', $id)->get();

        if(!is_null($car))
        {
            return response([
                'message' => 'Retrieve Car Success',
                'data' => $car
            ], 200);
        }
        else
        {
            return response([
                'message' => 'Car Not Found',
                'data' => null
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'service_mobil' => 'required',
            'jumlah_mobil' => 'required|numeric',
            'tools' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
            'usersID' => 'required|numeric'
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $car = Car::create($storeData);
        return response([
            'message' => 'Add Car Success',
            'data' => $car
        ], 200);
    }

    public function destroy($id)
    {
        $car = Car::find($id);

        if(is_null($car))
        {
            return response([
                'message' => 'Car Not Found',
                'data' => null
            ], 404);
        }
        
        if($car->delete())
        {
            return response([
                'message' => 'Delete Car Success',
                'data' => $car
            ], 200);
        }

        return response([
            'message' => 'Delete Car Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if(is_null($car))
        {
            return response([
                'message' => 'Car Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'service_mobil' => ['required'],
            'jumlah_mobil' => 'required|numeric',
            'tools' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $car->service_mobil = $updateData['service_mobil'];
        $car->jumlah_mobil = $updateData['jumlah_mobil'];
        $car->tools = $updateData['tools'];
        $car->tanggal = $updateData['tanggal'];

        if($car->save())
        {
            return response([
                'message' => 'Update Car Success',
                'data' => $car
            ], 200);
        }

        return response([
            'message' => 'Update Car Failed',
            'data' => null
        ], 400);
    }
}
