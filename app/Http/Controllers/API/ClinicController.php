<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Clinicdoctor;
use App\Models\Material;
use App\Models\Clinicadministrator;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DB;

class ClinicController extends Controller
{
    public function add(Request $request){
        
        Log::info('This is my log', ['request' => $request->all()]);
        //echo "<pre>"; print_r('test'); die;
        
        try{

            $validator = Validator::make($request->all(),[
                'clinic_name'=>'required|max:255',
                'insta_id'=>'required|max:255',
                'picture'=>'',
                'doctors'=>'',
                'administrators'=>'required'
            ]);

            if($validator->fails()){
                $response = [
                    'success'=>false,
                    'message'=>$validator->errors()
                ];

                return response()->json($response,401);
            }

            $jsonData = $request->json()->all();

            $clinic = new Clinic;
            $clinic->clinic_name = $jsonData['clinic_name'];
            $clinic->insta_id = $jsonData['insta_id'];
            $clinic->picture = $jsonData['picture'];

            /*
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $filename, 'public');
                $clinic->picture = $filename;
                //return response()->json(['message' => 'File uploaded successfully', 'path' => $path]);
            }
            */

            $clinic->save();

            if($clinic->id && $jsonData['doctors']){
                foreach($jsonData['doctors'] as $row){
                    $doctor = new Clinicdoctor;
                    $doctor->clinic_id = $clinic->id;
                    $doctor->doctor = $row['id'];
                    $doctor->save();
                }
            }

            if($clinic->id && $jsonData['administrators']){
                foreach($jsonData['administrators'] as $row){
                    $doctor = new Clinicadministrator;
                    $doctor->clinic_id = $clinic->id;
                    $doctor->name = $row['name'];
                    $doctor->email = $row['email'];
                    $doctor->password = bcrypt(($row['password']));
                    $doctor->save();
                }

                //register administrator in users table
                foreach($jsonData['administrators'] as $row){
                    $user = new User;
                    $user->first_name = $row['name'];
                    $user->last_name = $row['name'];
                    $user->user_type = 'admin';
                    $user->email = $row['email'];
                    $user->clinic_id = $clinic->id;
                    $user->password = bcrypt(($row['password']));
                    $user->secure = $row['password'];
                    $string = $row['name'];
                    $user->slug = $this->createSlug($string);
                    $user->save();
                }
            }

            $data = Clinic::with('administrators')->with('doctors')->where('mcl_clinic.id',$clinic->id)->first();

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

    public function userexist(Request $request){
        $email = $request->input('email');        
        if(!$email){
            $response = [
                'success'=>false,
                'message'=>'email parameter required'
            ];
            return response()->json($response,401);
        }

        $email = $request->input('email');        
        $user = User::where('email',$email)->first();
        $exist = true;
        if(!$user){
            $exist = false;
        }

        $response = [
            'success'=>true,
            'message'=>'',
            'userexist'=>$exist
        ];

        return response()->json($response,200);
    }

    public function createSlug($title, $id = 0)
    {
        $slug = Str::slug($title);
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        $i = 1;
        $is_contain = true;
        do {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                $is_contain = false;
                return $newSlug;
            }
            $i++;
        } while ($is_contain);
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return User::select('slug')->where('slug', 'like', $slug.'%')
        ->where('id', '<>', $id)
        ->get();
    }

    

    public function upload_picture(Request $request){
        //Log::info('This is my log', ['request' => $request->all()]);
        //echo "<pre>"; print_r('test'); die;
        try{

            $validator = Validator::make($request->all(),[               
                'picture'=>''
            ]);

            if($validator->fails()){
                $response = [
                    'success'=>false,
                    'message'=>$validator->errors()
                ];

                return response()->json($response,401);
            }
           
            $picture = '';
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $filename, 'public');
                $picture = $filename;                
                //return response()->json(['message' => 'File uploaded successfully', 'path' => $path]);
            }

            $file = url('/').Storage::url('uploads').'/'.$picture;
            $response = [
                'success'=> true,
                'message'=> 'picture uploaded successfully',
                'picture'=> $file
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=> false,
                'message'=> $e->getMessage(),
                'data'=> ''
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
                'doctors'=>'',
                'administrators'=>''
            ]);


            if($validator->fails()){
                $response = [
                    'success'=>false,
                    'message'=>$validator->errors()
                ];

                return response()->json($response,401);
            }

            $jsonData = $request->json()->all();

            $clinic_id = $id;
            $clinic = Clinic::find($clinic_id);
            $clinic->clinic_name = $jsonData['clinic_name'];
            $clinic->insta_id = $jsonData['insta_id'];
            $clinic->picture = $jsonData['picture'];

            /*
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $filename, 'public');
                $clinic->picture = $filename;
                //return response()->json(['message' => 'File uploaded successfully', 'path' => $path]);
            }
            */

            $clinic->save();

            if($clinic->id && $jsonData['doctors']){
                Clinicdoctor::where('clinic_id',$clinic->id)->delete();
                foreach($jsonData['doctors'] as $row){
                    $doctor = new Clinicdoctor;
                    $doctor->clinic_id = $clinic->id;
                    $doctor->doctor = $row['id'];
                    $doctor->save();
                }
            }

            if($clinic->id && $jsonData['administrators']){
                Clinicadministrator::where('clinic_id',$clinic->id)->delete();
                //User::where('clinic_id',$clinic->id)->delete();

                foreach($jsonData['administrators'] as $row){
                    $doctor = new Clinicadministrator;
                    $doctor->clinic_id = $clinic->id;
                    $doctor->name = $row['name'];
                    $doctor->email = $row['email'];

                    if($row['password'] && $row['password']!=""){
                        $doctor->password = bcrypt(($row['password']));
                    }

                    $doctor->save();
                }

                //register administrator in users table
                foreach($jsonData['administrators'] as $row){

                    $user = User::where('email',$row['email'])->first();
                    if($user){
                        $secure = $user->secure;
                        User::where('email',$row['email'])->delete();

                        $user = new User;
                        $user->first_name = $row['name'];
                        $user->last_name = $row['name'];
                        $user->user_type = 'admin';
                        $user->email = $row['email'];
                        $user->clinic_id = $clinic->id;
                        
                        if($row['password'] && $row['password']!=""){
                            $user->password = bcrypt(($row['password']));
                        }else{
                            $user->password = bcrypt(($secure));
                        }

                        $string = $row['name'];
                        $user->slug = $this->createSlug($string);
                        $user->save();
                    }else{

                        User::where('email',$row['email'])->delete();
                        $user = new User;
                        $user->first_name = $row['name'];
                        $user->last_name = $row['name'];
                        $user->user_type = 'admin';
                        $user->email = $row['email'];
                        $user->clinic_id = $clinic->id;
                        
                        if($row['password'] && $row['password']!=""){
                            $user->password = bcrypt(($row['password']));
                        }

                        $string = $row['name'];
                        $user->slug = $this->createSlug($string);
                        $user->save();                        
                    }
                }
            }

            $data = Clinic::with('administrators')->with('doctors')->where('mcl_clinic.id',$clinic->id)->first();

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
                'clinic'=>''
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
                $clinic = Clinic::orderBy('updated_at', 'DESC')->take($limit)->skip($offset)->with('administrators')->with('doctors')->get();
            }else{
                $clinic = Clinic::orderBy('updated_at', 'DESC')->with('administrators')->with('doctors')->get();
            }

            foreach($clinic as &$row){
                foreach($row->doctors as $val){
                    $user = User::find($val->doctor);
                    $fullName =  '';
                    if ($user) {
                        $fullName = $user->first_name.' '.$user->last_name;
                    }

                    $val->doctor = $fullName;
                }
            }

            $response = [
                'success'=>true,
                'message'=>'clinic list',
                'clinic'=>$clinic,
                'path'=>url('/').Storage::url('uploads'),
                'total_clinic'=>Clinic::count(),
                'limit'=>$limit,
                'offset'=>$offset
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'clinic'=>''
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
                    'clinic'=>[]
                ];

                return response()->json($response,401);
            }

            $clinic->status = $request->status;
            $clinic->save();

            $data = Clinic::with('administrators')->with('doctors')->where('mcl_clinic.id',$clinic->id)->first();
            $response = [
                'success'=>true,
                'message'=>'clinic status updated.',
                'clinic'=>$data
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'clinic'=>''
            ];

            return response()->json($response,401);
        }
    }

    public function index(Request $request){
        try{
            
            $clinic = Clinic::with('administrators')->with('doctors')->where('mcl_clinic.id',$request->id)->first();
            foreach($clinic->doctors as &$val){
                $user = User::find($val->doctor);
                $fullName =  '';
                if ($user) {
                    $fullName = $user->first_name.' '.$user->last_name;
                }

                $val->doctor = $fullName;
            }
            

            
            $response = [
                'success'=>true,                
                'message'=>'clinic list',
                'clinic'=>$clinic,
                'path'=>url('/').Storage::url('uploads')
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'clinic'=>'',
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
                'total'=>0,
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

    public function patient_list(Request $request){
        try{
            
            $patient = Patient::with('user')->get();
            
            $response = [
                'success'=>true,
                'total'=>$patient->count(),
                'message'=>'patient list',
                'patient'=>$patient
            ];

            return response()->json($response,200);
        }catch(\Exceptions $e){
            
            $response = [
                'success'=>false,
                'total'=>0,
                'message'=>$e->getMessage(),
                'patient'=>''
            ];

            return response()->json($response,401);
        }
    }

    public function patient(Request $request , $id){
        try{
            
            $patient = Patient::where('id',$request->id)->with('user')->first();
            if($patient){
                $response = [
                    'success'=>true,
                    'message'=>'patient info',
                    'patient'=>$patient
                ];

                return response()->json($response,200);
            }else{
                $response = [
                    'success'=>true,
                    'message'=>'no patient found',
                    'patient'=>$patient
                ];

                return response()->json($response,401);
            }
        }catch(\Exceptions $e){

            $response = [
                'success'=>false,
                'message'=>$e->getMessage(),
                'patient'=>''
            ];

            return response()->json($response,401);
        }
    }
}