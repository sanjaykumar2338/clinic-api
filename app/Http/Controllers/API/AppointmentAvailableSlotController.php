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
use App\Models\Clinicadministrator;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DB;

class AppointmentAvailableSlotController extends Controller {

	public function index(Request $request){
		if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $endDate = Carbon::parse($endDate)->addDay(); 

            $roomslots = Roomslots::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();
        }else{
            $roomslots = Roomslots::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();
        }

        $roomslots->each(function ($roomslot) {
		    $roomslot->days = unserialize($roomslot->days);
		    $roomslot->room_name = Room::where('id', $roomslot->room)->value('name');
		    $roomslot->doctor_name = Doctor::where('v3_doctors.id', $roomslot->doctor)->join('users','users.id','=','v3_doctors.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name');
		});
            
        $response = [
                'success'=>true,
                'message'=>'slots list',
                'slot'=>$roomslots
            ];

        return response()->json($response,200);
	}

	public function show($id)
    {
        // Fetch a single resource by ID
        $roomslot = Roomslots::find($id);
        if (!$roomslot) {
            return response()->json(['success'=>false,'message' => 'room slot not found'], 404);
        }

     	$roomslot->days = unserialize($roomslot->days);
	    $roomslot->room_name = Room::where('id', $roomslot->room)->value('name');
	    $roomslot->doctor_name = Doctor::where('v3_doctors.id', $roomslot->doctor)->join('users','users.id','=','v3_doctors.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name');

        return response()->json(['success'=>true,'material' => $roomslot]);
    }

	public function store(Request $request){
		// Create a new resource
	    $validator = Validator::make($request->all(),[
	        'doctor'=>'required',
	        'from'=>'required',
	        'to'=>'required',
	        'room'=>'required',
	        'duration'=>'',
	        'days'=>'required'
	    ]);

	    if($validator->fails()){
	        $response = [
	            'success'=>false,
	            'message'=>$validator->errors()
	        ];

	        return response()->json($response,401);
	    }

	    try{
		    $slot = new Roomslots;
		    $slot->doctor = $request->doctor;
		    $slot->from = $request->from;
		    $slot->to = $request->to;
		    $slot->room = $request->room;
		    $slot->duration = $request->duration;
		    $slot->days = serialize($request->days);
		    $slot->clinic_id = $request->user()->clinic_id;
		    $slot->admin = $request->user()->id;
		    $slot->save();

		    $response = [
                'success'=>true,
                'message'=>'data store successfully',
                'slot'=>$slot
            ];

            $slot->days = unserialize($slot->days);
        	return response()->json($response,200);
		}catch(\Exceptions $e){
			$response = [
                'success'=>false,
                'message'=>$e->getMessage()
            ];

        	return response()->json($response,404);
		}
	}
}