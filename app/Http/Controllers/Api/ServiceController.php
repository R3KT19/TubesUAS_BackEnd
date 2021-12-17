<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();

        if(count($services) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $services
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
        $service = Service::where('usersID', $id)->get();

        if(!is_null($service))
        {
            return response([
                'message' => 'Retrieve Service Success',
                'data' => $service
            ], 200);
        }
        else
        {
            return response([
                'message' => 'Service Not Found',
                'data' => null
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_jasa' => 'required',
            'alamat' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
            'jam' => 'required|numeric',
            'usersID' => 'required|numeric'
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $service = Service::create($storeData);
        return response([
            'message' => 'Add Service Success',
            'data' => $service
        ], 200);
    }

    public function destroy($id)
    {
        $service = Service::find($id);

        if(is_null($service))
        {
            return response([
                'message' => 'Service Not Found',
                'data' => null
            ], 404);
        }
        
        if($service->delete())
        {
            return response([
                'message' => 'Delete Service Success',
                'data' => $service
            ], 200);
        }

        return response([
            'message' => 'Delete Service Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if(is_null($service))
        {
            return response([
                'message' => 'Service Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_jasa' => ['required'],
            'alamat' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
            'jam' => 'required|numeric',
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $service->nama_jasa = $updateData['nama_jasa'];
        $service->alamat = $updateData['alamat'];
        $service->tanggal = $updateData['tanggal'];
        $service->jam = $updateData['jam'];

        if($service->save())
        {
            return response([
                'message' => 'Update Service Success',
                'data' => $service
            ], 200);
        }

        return response([
            'message' => 'Update Service Failed',
            'data' => null
        ], 400);
    }
}
