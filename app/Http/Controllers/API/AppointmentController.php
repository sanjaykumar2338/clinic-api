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
use App\Models\DoctorServices;
use App\Models\Clinicadministrator;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;

class AppointmentController extends Controller {

	public function index(Request $request){
		if($request->date){
            $date = $request->date;
            $appointment = Appointment::whereDate('date',$date)->where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id);
        }else{
            $appointment = Appointment::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id);
        }

        $res = $appointment->get();

        $res->each(function ($appointment) {
        	$appointment->isConfirmed = true;
		    $appointment->appointmentSlot = unserialize($appointment->slot);
		    unset($appointment->slot);
		    $appointment->room = array('id'=>$appointment->room,'name'=>Room::where('id', $appointment->room)->value('name'));
		    $appointment->service = array('id'=>$appointment->service,'name'=>DoctorServices::where('id', $appointment->service)->value('name'));
		    $appointment->doctor = array('id'=>$appointment->doctor,'name'=>Doctor::where('v3_doctors.id', $appointment->doctor)->join('users','users.id','=','v3_doctors.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name'));

		    $appointment->patient = array('id'=>$appointment->patient,'name'=>Patient::where('v3_patients.id', $appointment->patient)->join('users','users.id','=','v3_patients.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name'));


		});
            
        $response = [
                'success'=>true,
                'message'=>'appointments list',
                'appointment'=>$res
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
	        'service'=>'',
	        'description'=>'',
	        'slot'=>'required',
	    ]);

	    if($validator->fails()){
	        $response = [
	            'success'=>false,
	            'message'=>$validator->errors()->first()
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
	        'service'=>'',
	        'description'=>'',
	        'slot'=>'required',
	    ]);

	    if($validator->fails()){
	        $response = [
	            'success'=>false,
	            'message'=>$validator->errors()->first()
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

     	$appointment->isConfirmed = true;
	    $appointment->appointmentSlot = unserialize($appointment->slot);
	    unset($appointment->slot);
	    $appointment->room = array('id'=>$appointment->room,'name'=>Room::where('id', $appointment->room)->value('name'));
	    $appointment->service = array('id'=>$appointment->service,'name'=>DoctorServices::where('id', $appointment->service)->value('name'));
	    $appointment->doctor = array('id'=>$appointment->doctor,'name'=>Doctor::where('v3_doctors.id', $appointment->doctor)->join('users','users.id','=','v3_doctors.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name'));

	    $appointment->patient = array('id'=>$appointment->patient,'name'=>Patient::where('v3_patients.id', $appointment->patient)->join('users','users.id','=','v3_patients.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name'));

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

    public function doctor_services(Request $request, $id){
    	if(!$id || $id==''){
    		return response()->json(['message' => 'doctor is required','success'=>false], 404);
    	}

    	$services = DoctorServices::where('v3_doctor_id',$id)->get();

    	$response = [
                'success'=>true,
                'message'=>'services list',
                'services'=>$services
            ];

        return response()->json($response,200);
    }

    public function deletetime(Request $request){
		$resource = Roomslots::find($request->id);
		$days = unserialize($resource->days);
		$data['days'] = $days;
		//echo "<pre>"; print_r($days);  die;


		$targetDay = $request->day;
		$startTimeToDelete = $request->starttime;
		$endTimeToDelete = $request->endtime;

		$main_array = array();
		foreach ($data['days'] as $key=>&$day) {
		    if ($day['day'] === $targetDay) {
		       	//echo "<pre>"; print_r($day); die;

		       	$newArray = [];
		       	foreach ($day['slots'][0]['slotsduration'] as $slot) {
		       		//echo $startTimeToDelete; die;
		       		if($slot['startTime']!=$startTimeToDelete && $slot['endTime']!=$endTimeToDelete){
		       			$newArray[] = $slot;
		       		}
	       		}

	       		//echo $day['day'];
	       		$day['slots'][0]['slotsduration'] = $newArray;
		    }else{
		    	//$main_array[] = $day;
		    }
		}


		//die;
		//echo "<pre>"; print_r($data); 
		//die;

		$resource->days = serialize($data['days']);
		$resource->save();

		$response = [
                'success'=>true,
                'message'=>'slot deleted successfully',
                'slot'=>$resource
            ];

    	return response()->json($response,200);
	}
}