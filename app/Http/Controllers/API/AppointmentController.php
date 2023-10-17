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
use App\Models\Room;
use App\Models\Roomslots;
use App\Models\Appointment;
use App\Models\Clinicadministrator;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;

class AppointmentController extends Controller {

	public function index(Request $request){
		if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $endDate = Carbon::parse($endDate)->addDay(); 

            $appointment = Appointment::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id);
        }else{
            $appointment = Appointment::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id);
        }

        $res = $appointment->get();

        $res->each(function ($appointment) {
		    $appointment->slot = unserialize($appointment->slot);
		    $appointment->room_name = Room::where('id', $appointment->room)->value('name');
		    $appointment->doctor_name = Doctor::where('v3_doctors.id', $appointment->doctor)->join('users','users.id','=','v3_doctors.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name');
		});
            
        $response = [
                'success'=>true,
                'message'=>'appointments list',
                'slot'=>$res
            ];

        return response()->json($response,200);
	}

	public function store(Request $request){
		// Create a new resource
	    $validator = Validator::make($request->all(),[
	        'doctor'=>'required',
	        'date'=>'required',
	        'room'=>'required',
	        'duration'=>'required',
	        'patient'=>'required',
	        'service'=>'required',
	        'description'=>'required',
	        'slot'=>'required',
	    ]);

	    if($validator->fails()){
	        $response = [
	            'success'=>false,
	            'message'=>$validator->errors()
	        ];

	        return response()->json($response,401);
	    }

	    try{
		    $appointment = new Appointment;
		    $appointment->clinic_id = $request->user()->clinic_id;
		    $appointment->admin_id = $request->user()->id;
		    $appointment->doctor = $request->doctor;
		    $appointment->date = $request->date;
		    $appointment->room = $request->room;
		    $appointment->duration = $request->duration;
		    $appointment->patient = $request->patient;
		    $appointment->service = $request->service;
		    $appointment->description = $request->description;
		    $appointment->slot = serialize($request->slot);
		    $appointment->save();

		    $response = [
                'success'=>true,
                'message'=>'Appointment scheduled successfully',
                'appointment'=>$appointment
            ];

            $appointment->slot = unserialize($appointment->slot);
        	return response()->json($response,200);
		}catch(\Exceptions $e){
			$response = [
                'success'=>false,
                'message'=>$e->getMessage()
            ];

        	return response()->json($response,404);
		}
	}

	public function update(Request $request, $id){

		$appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'appointment not found','success'=>false], 404);
        }

		// Create a new resource
	    $validator = Validator::make($request->all(),[
	        'doctor'=>'required',
	        'date'=>'required',
	        'room'=>'required',
	        'duration'=>'required',
	        'patient'=>'required',
	        'service'=>'required',
	        'description'=>'required',
	        'slot'=>'required',
	    ]);

	    if($validator->fails()){
	        $response = [
	            'success'=>false,
	            'message'=>$validator->errors()
	        ];

	        return response()->json($response,401);
	    }

	    try{
		    $appointment->clinic_id = $request->user()->clinic_id;
		    $appointment->admin_id = $request->user()->id;
		    $appointment->doctor = $request->doctor;
		    $appointment->date = $request->date;
		    $appointment->room = $request->room;
		    $appointment->duration = $request->duration;
		    $appointment->patient = $request->patient;
		    $appointment->service = $request->service;
		    $appointment->description = $request->description;
		    $appointment->slot = serialize($request->slot);
		    $appointment->save();

		    $response = [
                'success'=>true,
                'message'=>'Appointment updated successfully',
                'appointment'=>$appointment
            ];

            $appointment->slot = unserialize($appointment->slot);
        	return response()->json($response,200);
		}catch(\Exceptions $e){
			$response = [
                'success'=>false,
                'message'=>$e->getMessage()
            ];

        	return response()->json($response,404);
		}
	}

	public function show($id)
    {
        // Fetch a single resource by ID
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['success'=>false,'message' => 'appointment not found'], 404);
        }

     	$appointment->slot = unserialize($appointment->slot);
	    $appointment->room_name = Room::where('id', $appointment->room)->value('name');
	    $appointment->doctor_name = Doctor::where('v3_doctors.id', $appointment->doctor)->join('users','users.id','=','v3_doctors.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name');

        return response()->json(['success'=>true,'appointment' => $appointment]);
    }

    public function destroy($id){
        // Delete a resource
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'appointment not found','success'=>false], 404);
        }

        $appointment->update(['is_deleted'=>1]);
        return response()->json(['message' => 'appointment deleted successfully!!!','success'=>true]);
    }
}