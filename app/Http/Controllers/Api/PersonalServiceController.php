<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\PersonalService;

class PersonalServiceController extends Controller
{
    public function index()
    {
        $personalservices = PersonalService::all();

        if(count($personalservices) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $personalservices
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
        $personalservice = PersonalService::where('usersID', $id)->get();

        if(!is_null($personalservice))
        {
            return response([
                'message' => 'Retrieve PersonalService Success',
                'data' => $personalservice
            ], 200);
        }
        else
        {
            return response([
                'message' => 'PersonalService Not Found',
                'data' => null
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'personal_service' => 'required',
            'durasi' => 'required|numeric',
            'paket' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
            'usersID' => 'required|numeric'
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $personalservice = PersonalService::create($storeData);
        return response([
            'message' => 'Add PersonalService Success',
            'data' => $personalservice
        ], 200);
    }

    public function destroy($id)
    {
        $personalservice = PersonalService::find($id);

        if(is_null($personalservice))
        {
            return response([
                'message' => 'PersonalService Not Found',
                'data' => null
            ], 404);
        }
        
        if($personalservice->delete())
        {
            return response([
                'message' => 'Delete PersonalService Success',
                'data' => $personalservice
            ], 200);
        }

        return response([
            'message' => 'Delete PersonalService Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $personalservice = PersonalService::find($id);

        if(is_null($personalservice))
        {
            return response([
                'message' => 'PersonalService Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'personal_service' => ['required'],
            'durasi' => 'required|numeric',
            'paket' => 'required',
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $personalservice->personal_service = $updateData['personal_service'];
        $personalservice->durasi = $updateData['durasi'];
        $personalservice->paket = $updateData['paket'];
        $personalservice->tanggal = $updateData['tanggal'];

        if($personalservice->save())
        {
            return response([
                'message' => 'Update PersonalService Success',
                'data' => $personalservice
            ], 200);
        }

        return response([
            'message' => 'Update PersonalService Failed',
            'data' => null
        ], 400);
    }
}
