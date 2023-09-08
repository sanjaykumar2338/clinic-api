<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Clinicdoctor;
use App\Models\Clinicadministrator;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClinicController extends Controller
{
    public function add(Request $request){
            //Log::info('This is my log', ['request' => $request->all()]);
            //echo "<pre>"; print_r('test'); die;
        try{

            $validator = Validator::make($request->all(),[
                'clinic_name'=>'required|max:255',
                'insta_id'=>'required|max:255',
                'picture'=>'required|max:255',
                'doctors'=>'required',
                'administrator'=>'required'
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

            $data = Clinic::with('administrator')->with('doctor')->where('mcl_clinic.id',$clinic->id)->first();

            $response = [
                'success'=>true,
                'message'=>'Clinic add successfully',
                'clinic'=>$data
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'data'=>''
            ];

            return response()->json($response,401);
        }
    }

    public function update(Request $request, $id){
        
        //Log::info('This is my log', ['request' => $request->all()]);
        //echo "<pre>"; print_r('test'); die;

        try{
            $validator = Validator::make($request->all(),[
                'clinic_name'=>'required|max:255',
                'insta_id'=>'required|max:255',
                'picture'=>'',
                'doctors'=>'required',
                'administrator'=>'required'
            ]);


            if($validator->fails()){
                $response = [
                    'success'=>false,
                    'message'=>$validator->errors()
                ];

                return response()->json($response,401);
            }

            $clinic_id = $id;
            $clinic = Clinic::find($clinic_id);
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
                Clinicdoctor::where('clinic_id',$clinic->id)->delete();
                foreach(json_decode($request->doctors) as $row){
                    $doctor = new Clinicdoctor;
                    $doctor->clinic_id = $clinic->id;
                    $doctor->doctor = $row->doctor_name;
                    $doctor->save();
                }
            }

            if($clinic->id && $request->administrator){
                Clinicadministrator::where('clinic_id',$clinic->id)->delete();
                foreach(json_decode($request->administrator) as $row){
                    $doctor = new Clinicadministrator;
                    $doctor->clinic_id = $clinic->id;
                    $doctor->name = $row->administrator_name;
                    $doctor->email = $row->administrator_email;
                    $doctor->password = bcrypt(($row->administrator_password));
                    $doctor->save();
                }
            }

            $data = Clinic::with('administrator')->with('doctor')->where('mcl_clinic.id',$clinic->id)->first();

            $response = [
                'success'=>true,
                'message'=>'Clinic updated successfully',
                'clinic'=>$data
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'data'=>''
            ];

            return response()->json($response,401);
        }
    }

    public function list(Request $request){
        //Log::info('This is my log', ['request' => $request->all()]);
        try{
            $limit = $request->limit;
            $offset = $request->offset;

            //$clinic = Clinic::take($limit)->skip($offset)->with('administrator')->with('doctor')->get();
            if($limit!="" && $offset!=""){
                $clinic = Clinic::take($limit)->skip($offset)->with('administrator')->with('doctor')->get();
            }else{
                $clinic = Clinic::orderBy('id', 'DESC')->with('administrator')->with('doctor')->get();
            }

            $response = [
                'success'=>true,
                'message'=>'clinic all data',
                'data'=>$clinic,
                'path'=>url('/').Storage::url('uploads'),
                'total clinic'=>Clinic::count(),
                'limit'=>$limit,
                'offset'=>$offset
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'data'=>''
            ];

            return response()->json($response,401);
        }
    }

    public function status_update(Request $request){
        try{
            
            $clinic = Clinic::find($request->id);
            
            if(!$clinic){
                 $response = [
                    'success'=>false,
                    'message'=>'clinic not found',
                    'data'=>[]
                ];

                return response()->json($response,401);
            }

            $clinic->status = $request->status;
            $clinic->save();

            $data = Clinic::with('administrator')->with('doctor')->where('mcl_clinic.id',$clinic->id)->first();
            $response = [
                'success'=>true,
                'message'=>'clinic status updated.',
                'data'=>$data
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'data'=>''
            ];

            return response()->json($response,401);
        }
    }

    public function index(Request $request){
        try{
            
            $clinic = Clinic::with('administrator')->with('doctor')->where('mcl_clinic.id',$request->id)->first();
            
            $response = [
                'success'=>true,                
                'message'=>'clinic list',
                'data'=>$clinic,
                'path'=>url('/').Storage::url('uploads')
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'data'=>'',
                'total'=>0
            ];

            return response()->json($response,401);
        }
    }

    public function doctor_list(Request $request){
        try{
            
            $doctor = Doctor::with('user')->get();
            
            $response = [
                'success'=>true,
                'total'=>$doctor->count(),
                'message'=>'doctor list',
                'data'=>$doctor
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'total'=>$doctor->count(),
                'message'=>$e->getMessage(),
                'data'=>''
            ];

            return response()->json($response,401);
        }
    }

    public function doctor(Request $request , $id){
        try{
            
            $doctor = Doctor::where('id',$request->id)->with('user')->first();
            if($doctor){
                $response = [
                    'success'=>true,
                    'message'=>'doctor info',
                    'data'=>$doctor
                ];

                return response()->json($response,200);
            }else{
                $response = [
                    'success'=>true,
                    'message'=>'no doctor found',
                    'data'=>$doctor
                ];

                return response()->json($response,401);
            }
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'data'=>''
            ];

            return response()->json($response,401);
        }
    }
}