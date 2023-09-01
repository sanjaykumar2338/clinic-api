<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Clinicdoctor;
use App\Models\Clinicadministrator;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClinicController extends Controller
{
    public function add(Request $request){
        Log::info('This is my log', ['request' => $request->all()]);
        //echo "<pre>"; print_r('test'); die;
        $validator = Validator::make($request->all(),[
            'clinic_name'=>'required',
            'insta_id'=>'required'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $clinic = new Clinic;
        $clinic->clinic_name = $request->clinic_name;
        $clinic->insta_id = $request->insta_id;

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $clinic->picture = $filename;
            //return response()->json(['message' => 'File uploaded successfully', 'path' => $path]);
        }

        $clinic->save();

        if($clinic->id && $request->doctors){
            foreach(json_decode($request->doctors) as $row){
                $doctor = new Clinicdoctor;
                $doctor->clinic_id = $clinic->id;
                $doctor->doctor = $row->doctor_name;
                $doctor->save();
            }
        }

        if($clinic->id && $request->administrator){
            foreach(json_decode($request->administrator) as $row){
                $doctor = new Clinicadministrator;
                $doctor->clinic_id = $clinic->id;
                $doctor->name = $row->administrator_name;
                $doctor->email = $row->administrator_email;
                $doctor->password = bcrypt(($row->administrator_password));
                $doctor->save();
            }
        }

        $response = [
            'success'=>true,
            'message'=>'Clinic add successfully',
            'clinic'=>Clinic::find($clinic->id)
        ];

        return response()->json($response,200);
    }

    public function list(Request $request){
        Log::info('This is my log', ['request' => $request->all()]);
        $limit = $request->input('limit', 5); // Default limit is 10, change as needed
        $offset = $request->input('offset', 0);

        $page = $request->offset;
        if($page!=0){
            $page = $page -1;
        }

        $clinic = Clinic::take($limit)->skip($page)->with('administrator')->with('doctor')->get();

        $response = [
            'success'=>true,
            'message'=>'clinic all data',
            'data'=>$clinic,
            'path'=>url('/').Storage::url('uploads'),
            'total'=>Clinic::count()
        ];

        return response()->json($response,200);
    }
}