<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Provider;
use App\Models\Campaign;
use App\Models\Campaignstatistics;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\Services;
use App\Models\Specialist;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all resources
        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $endDate = Carbon::parse($endDate)->addDay(); 

            $campaign = Campaign::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();
        }else{
            $campaign = Campaign::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();
        }
            
        $file = url('/').Storage::url('campaign');
        $response = [
                'success'=>true,
                'message'=>'campaign list',
                'campaign'=>$campaign,
                'path'=>$file
            ];

        return response()->json($response,200);
    }

    public function statistics(Request $request){
        $statistics = Campaignstatistics::with('campaign')->orderBy('created_at','desc')->get();
        $response = [
                'success'=>true,
                'message'=>'campaign statistics list',
                'statistics'=>$statistics
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['success'=>false,'message' => 'campaign not found'], 404);
        }

        $file = url('/').Storage::url('campaign');
        return response()->json(['success'=>true,'campaign' => $campaign,'path'=>$file]);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:mcl_campaign',
            'subject'=>'required',
            'date'=>'required',
            'schedule'=>'required',
            'message'=>'required',
            'header_image'=>'',
            'main_image'=>'',
            'final_image'=>'',
            'header_image_url'=>'',
            'main_image_url'=>'',
            'final_image_url'=>''
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $campaign = new Campaign;
        $campaign->name = $request->name;
        $campaign->subject = $request->subject;
        $campaign->date = $request->date;
        $campaign->schedule = $request->schedule;
        $campaign->message = $request->message;
        $campaign->header_image_url = $request->header_image_url;
        $campaign->main_image_url = $request->main_image_url;
        $campaign->final_image_url = $request->final_image_url;
        $campaign->clinic_id = $request->user()->clinic_id;

        if ($request->header_image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->header_image));
            $storageLocation = 'public/campaign';
            $header_image = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$header_image", $imageData);
            $campaign->header_image = $header_image;
        }

        if ($request->main_image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->main_image));
            $storageLocation = 'public/campaign';
            $main_image = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$main_image", $imageData);
            $campaign->main_image = $main_image;
        }

        if ($request->final_image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->final_image));
            $storageLocation = 'public/campaign';
            $final_image = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$final_image", $imageData);
            $campaign->final_image = $final_image;
        }

        $file = url('/').Storage::url('campaign');
        $campaign->save();
        $response = [
                'success'=>true,
                'message'=>'campaign add successfully',
                'campaign'=>$campaign,
                'path'=>$file
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['success'=>false,'message' => 'campaign not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:mcl_campaign,name,'.$id,
            'subject'=>'required',
            'date'=>'required',
            'schedule'=>'required',
            'message'=>'required',
            'header_image'=>'',
            'main_image'=>'',
            'final_image'=>'',
            'header_image_url'=>'',
            'main_image_url'=>'',
            'final_image_url'=>''
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $campaign->name = $request->name;
        $campaign->subject = $request->subject;
        $campaign->date = $request->date;
        $campaign->schedule = $request->schedule;
        $campaign->message = $request->message;
        $campaign->header_image_url = $request->header_image_url;
        $campaign->main_image_url = $request->main_image_url;
        $campaign->final_image_url = $request->final_image_url;
        $campaign->clinic_id = $request->user()->clinic_id;

        if ($request->header_image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->header_image));
            $storageLocation = 'public/campaign';
            $header_image = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$header_image", $imageData);
            $campaign->header_image = $header_image;
        }

        if ($request->main_image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->main_image));
            $storageLocation = 'public/campaign';
            $main_image = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$main_image", $imageData);
            $campaign->main_image = $main_image;
        }

        if ($request->final_image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->final_image));
            $storageLocation = 'public/campaign';
            $final_image = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$final_image", $imageData);
            $campaign->final_image = $final_image;
        }

        $campaign->save();
        $response = [
                'success'=>true,
                'message'=>'campaign update successfully',
                'campaign'=>$campaign
            ];

        return response()->json($response,200);
    }

    public function destroy($id)
    {
        // Delete a resource
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['message' => 'campaign not found','success'=>false], 404);
        }
        $campaign->update(['is_deleted'=>1]);
        return response()->json(['message' => 'campaign deleted','success'=>true]);
    }

    public function specialty(Request $request){        
        $specialty = Specialty::all();
        return response()->json(['message' => 'specialty list','success'=>true,'specialty'=>$specialty]);   
    }

    public function specialist(Request $request,$id){        
        $specialty = Specialist::join('v3_doctor_services','v3_doctor_services.v3_speciality_id','=','v3_specialities.id')->select('v3_specialities.*','v3_doctor_services.v3_service_id as service_id')->where('v3_doctor_services.v3_speciality_id',$id)->get();
        return response()->json(['message' => 'specialist list','success'=>true,'specialty'=>$specialty]);   
    }

    public function services(Request $request, $id){        
        $services = \DB::table('v3_doctor_services')->join('v3_speciality_services','v3_speciality_services.id','=','v3_doctor_services.v3_service_id')->where('v3_doctor_services.v3_service_id',$id)->get();
        return response()->json(['message' => 'services list','success'=>true,'Services'=>$services]);   
    }

    public function send(Request $request){
        
        $validator = Validator::make($request->all(),[
            'patient_with'=>'required|in:with_appointment,without_appointment',
            'appointment_from'=>'nullable|date',
            'appointment_to'=>'nullable|date',
            'age_from'=>'nullable|numeric',
            'age_to'=>'nullable|numeric'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }


        if($request->patient_with=='with_appointment'){
            $query = Patient::query();
            $query->join('appointments','appointments.patient_id','=','v3_patients.id')->select('v3_patients.*');

            if($request->appointment_from  && $request->appointment_to){
                $from = $request->appointment_from;
                $to = $request->appointment_to;
               
                $query->whereBetween('appointments.appointment_date',[Carbon::parse($from)->format('Y-m-d 00:00:00'),Carbon::parse($to)->format('Y-m-d 23:59:59')]);
            }

            if($request->age_from  && $request->age_to){

                $minAge = $request->age_from;
                $maxAge = $request->age_to;
                
                $maxBirthdate = Carbon::now()->subYears($minAge)->format('Y-m-d');
                $minBirthdate = Carbon::now()->subYears($maxAge + 1)->format('Y-m-d');

                $query->whereBetween('v3_patients.birth_date', [$minBirthdate, $maxBirthdate]);
            }

            $query->join('v3_appointments','v3_appointments.patient_id','=','v3_patients.id');

            if($request->specialty){
                $query->join('v3_doctor_services','v3_doctor_services.id','=','v3_appointments.service_id')->where('v3_doctor_services.v3_speciality_id',$request->specialty);
            }

            if($request->specialist){
                $query->join('v3_doctor_services as t','t.id','=','v3_appointments.service_id')->where('t.v3_doctor_id',$request->specialist);
            }

            if($request->services){
                $query->where('v3_appointments.service_id',$request->services);
            }

            $results = $query->get();

            $response = [
                'success'=>true,
                'message'=>'patient list',
                'total'=>count($results),
                'patient'=>$results
            ];

            return response()->json($response,200);
        }

        if($request->patient_with=='without_appointment'){
            $query = Patient::query();
            $results = $query->get();

            if($request->age_from  && $request->age_to){

                $minAge = $request->age_from;
                $maxAge = $request->age_to;
                
                $maxBirthdate = Carbon::now()->subYears($minAge)->format('Y-m-d');
                $minBirthdate = Carbon::now()->subYears($maxAge + 1)->format('Y-m-d');

                $query->whereBetween('v3_patients.birth_date', [$minBirthdate, $maxBirthdate]);
            }

            $results = $query->get();

            $response = [
                'success'=>true,
                'message'=>'patient list',
                'patient'=>$results
            ];

            return response()->json($response,200);
        }
    }
}
