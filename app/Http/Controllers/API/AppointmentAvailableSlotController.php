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
use Carbon\Carbon;
use DB;

class AppointmentAvailableSlotController extends Controller {

	public function index(Request $request){
		if($request->from && $request->to){
            $fromDate = $request->from;
            $toDate = $request->to;
            //$endDate = Carbon::parse($endDate)->addDay(); 

            //echo $fromDate, $toDate; die;

            //$roomslots = Roomslots::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id);

            $roomslots = Roomslots::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id);
        }else{
            $roomslots = Roomslots::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id);
        }

        if($request->doctor && $request->doctor!=""){
        	$roomslots->where('doctor', $request->doctor);
        }

        if($request->from && $request->to){
        	$fromDate = $request->from;
            $toDate = $request->to;
            
            /*
        	$res = $roomslots->get()->filter(function ($slot) use ($fromDate, $toDate) {
		        // Unserialize the "days" field
		        $days = unserialize($slot->days);

		        // Check if at least one "date" in the unserialized data falls within the date range
		        foreach ($days as $day) {
		            if (isset($day['date']) && $day['date'] >= $fromDate && $day['date'] <= $toDate) {
		                return true;
		            }
		        }

		        return false;
		    });
		    */

		    $res = $roomslots->get();
    	}else{
    		$res = $roomslots->get();
    	}

    	$fromDate = $request->from;
    	$toDate = $request->to;
        $res->each(function ($roomslot) use ($fromDate, $toDate) {
		    $roomslot->days = unserialize($roomslot->days);
		    $all_slots = array();
		    if($fromDate && $toDate){
			    foreach ($roomslot->days as $day) {
		            if (isset($day['date']) && $day['date'] >= $fromDate && $day['date'] <= $toDate) {
		                $all_slots[] = $day;
		            }
		        }

		        $roomslot->days = $all_slots;
	        }

		    $roomslot->room_name = Room::where('id', $roomslot->room)->value('name');
		    $roomslot->doctor_name = Doctor::where('v3_doctors.id', $roomslot->doctor)->join('users','users.id','=','v3_doctors.user_id')->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name) as full_name"))->value('full_name');
		});

		$newArray = array();
		foreach($res as $row){
			if(!empty($row->days)){
				$newArray[] = $row;
			}
		}
            
        $response = [
                'success'=>true,
                'message'=>'slots list',
                'total'=>count($newArray),
                'slot'=>$newArray
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
	            'message'=>$validator->errors()->first()
	        ];

	        return response()->json($response,401);
	    }

	    //echo "<pre>"; print_r($request->all()); die;

	    $data = $request->all();

		// Calculate the slot intervals based on the given duration
		$duration = $data['duration']; // Change this as needed
		$startTime = strtotime($data['days'][0]['slots'][0]['startTime']);
		$endTime = strtotime($data['days'][0]['slots'][0]['endTime']);

		$slotIntervals = [];
		while ($startTime + $duration * 60 <= $endTime) {
		    $endTimeSlot = $startTime + $duration * 60;
		    $slotIntervals[] = [
		        'startTime' => date('H:i', $startTime),
		        'endTime' => date('H:i', $endTimeSlot)
		    ];
		    $startTime = $endTimeSlot;
		}

		// Update the JSON data with the calculated slots
		foreach ($data['days'] as &$day) {
		    foreach ($day['slots'] as &$slot) {
		        $slot['slotsduration'] = $slotIntervals;
		    }
		}

		// Convert the data back to JSON
		//$newJsonData = json_encode($data, JSON_PRETTY_PRINT);
		//echo $newJsonData; die;
		//echo "<pre>"; print_r($data['days']); die;

	    try{
		    $slot = new Roomslots;
		    $slot->doctor = $request->doctor;
		    $slot->from = $request->from;
		    $slot->to = $request->to;
		    $slot->room = $request->room;
		    $slot->duration = $request->duration;
		    $slot->days = serialize($data['days']);
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

		$resource->days = serialize($data);
		$resource->save();

		$response = [
                'success'=>true,
                'message'=>'slot deleted successfully',
                'slot'=>$resource
            ];

    	return response()->json($response,200);
	}

	public function destroy($id){
        // Delete a resource
        $resource = Roomslots::find($id);
        if (!$resource) {
            return response()->json(['message' => 'room slot not found','success'=>false], 404);
        }

        $resource->update(['is_deleted'=>1]);
        return response()->json(['message' => 'room slot deleted','success'=>true]);
    }
}