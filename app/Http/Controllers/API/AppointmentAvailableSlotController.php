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
		});
            
        $response = [
                'success'=>true,
                'message'=>'slots list',
                'slot'=>$roomslots
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
		    $slot->date = $request->date;
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