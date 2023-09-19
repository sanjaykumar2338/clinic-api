<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Revenue;
use App\Models\Expenses;
use App\Models\Doctor;
use App\Models\Clinicdoctor;
use App\Models\RevenuePatient;
use App\Models\Clinicadministrator;
use App\Models\PatientDocument;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\Carbon;

class PatientBalanceController extends Controller
{

	public function index(Request $request){
		//echo "<pre>"; print_r($request->user()->clinic_id); die;
		$res = RevenuePatient::join('mcl_revenue','mcl_revenue.id','=','mcl_revenue_patient.revenue')->join('v3_patients','v3_patients.id','=','mcl_revenue.patient')->join('users','users.id','=','v3_patients.user_id')->join('v3_doctors','v3_doctors.id','=','mcl_revenue.doctor')->join('users as doctor_data','doctor_data.id','=','v3_doctors.user_id')->where('mcl_revenue.clinic_id',$request->user()->clinic_id)->selectRaw('CONCAT(users.first_name, " ", users.last_name) as name, mcl_revenue.id, mcl_revenue.price as total_amount,mcl_revenue.amount_paid as paid_amount,mcl_revenue.price - mcl_revenue.amount_paid as pending_amount,CONCAT(doctor_data.first_name, " ", doctor_data.last_name) as doctor,mcl_revenue.patient')->orderBy('mcl_revenue_patient.id','desc')->get();

		//echo "<pre>"; print_r($res); die;
		return response()->json(['success'=>true,'message'=>'patient balance list','patientbalance' => $res]);
	}

	public function balance(Request $request, $id){
		//echo "<pre>"; print_r($request->user()->clinic_id); die;
		$res = RevenuePatient::join('mcl_revenue','mcl_revenue.id','=','mcl_revenue_patient.revenue')->join('v3_patients','v3_patients.id','=','mcl_revenue_patient.patient')->join('users','users.id','=','v3_patients.user_id')->join('v3_doctors','v3_doctors.id','=','mcl_revenue.doctor')->join('users as doctor_data','doctor_data.id','=','v3_doctors.user_id')->where('mcl_revenue_patient.patient',$id)->selectRaw('CONCAT(users.first_name, " ", users.last_name) as patient, mcl_revenue_patient.id, mcl_revenue.price as total_amount,mcl_revenue.amount_paid as paid,mcl_revenue.price - mcl_revenue.amount_paid as pending,CONCAT(doctor_data.first_name, " ", doctor_data.last_name) as treating_physician,mcl_revenue_patient.updated_at as last_update')->orderBy('mcl_revenue_patient.id','desc')->get();

		$patient = Patient::with('user')->find($id);
		$patient_name = $patient->user ? $patient->first_name.' '.$patient->last_name : '';
		$treating_physician = RevenuePatient::where('patient',$id)->join('mcl_revenue','mcl_revenue.id','=','mcl_revenue.id')->select('doctor_data.*')->join('v3_doctors','v3_doctors.id','=','mcl_revenue.doctor')->join('users as doctor_data','doctor_data.id','=','v3_doctors.user_id')->orderBy('created_at','desc')->first();

		$treating_physician_name = $treating_physician ? $treating_physician->first_name.' '.$treating_physician->last_name:'';

		//echo "<pre>"; print_r($treating_physician_name); die;
		$data = array('patient_name'=>$patient_name,'treating_physician'=>$treating_physician_name,'medical_record_number'=>'','balance'=>$res);

		return response()->json(['success'=>true,'message'=>'patient balance list','data' => $data]);
	}

	public function movements(Request $request, $id){
		if($request->from && $request->to){
                $startDate = $request->from;
                $endDate = $request->to;

            	$revenue = Revenue::join('mcl_revenue_patient','mcl_revenue_patient.revenue','=','mcl_revenue.id')->whereBetween('mcl_revenue.created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('mcl_revenue_patient.patient',$id)->select('mcl_revenue.*')->orderBy('mcl_revenue.created_at','desc')->get();

                $expenses = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('patient',$id)->orderBy('created_at','desc')->get();
        }else{
                $revenue = Revenue::join('mcl_revenue_patient','mcl_revenue_patient.revenue','=','mcl_revenue.id')->where('mcl_revenue_patient.patient',$id)->select('mcl_revenue.*')->orderBy('mcl_revenue.created_at','desc')->get();

                $expenses = Expenses::where('patient',$id)->orderBy('created_at','desc')->get();
        }
        
        $mergedData = $revenue->concat($expenses);
        $sortedData = $mergedData->sortBy('created_at');

        $arr = [];
        if($sortedData){
            foreach($sortedData as $row){
                $arr[] = $row;
            }
        }

        $response = [
                'success'=>true,
                'message'=>'movements lists',
                'data'=>$arr
            ];

        return response()->json($response,200);
	}

	public function document(Request $request, $id){
		try{

            $validator = Validator::make($request->all(),[               
                'document'=> 'required|file|mimes:jpeg,png,pdf|max:2048',
                'type'=>'required|in:Identificación oficial,Constanica SAT,Comprobante de domicilio,Póliza de seguro'
            ]);

            if($validator->fails()){
                $response = [
                    'success'=>false,
                    'message'=>$validator->errors()
                ];

                return response()->json($response,401);
            }
           
            $document = '';
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $filename, 'public');
                $document = $filename; 
            }

            $file = url('/').Storage::url('uploads').'/'.$document;

            $rec = new PatientDocument;
            $rec->patient = $id;
            $rec->document = $document;
            $rec->type = $request->type;
            $rec->save();

            $response = [
                'success'=> true,
                'message'=> 'document uploaded successfully',
                'document'=> $file
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

	public function documentlist(Request $request, $id){

		$patient = Patient::with('user')->find($id);
		$patient_name = $patient->user ? $patient->first_name.' '.$patient->last_name : '';
		$treating_physician = RevenuePatient::where('patient',$id)->join('mcl_revenue','mcl_revenue.id','=','mcl_revenue.id')->select('doctor_data.*')->join('v3_doctors','v3_doctors.id','=','mcl_revenue.doctor')->join('users as doctor_data','doctor_data.id','=','v3_doctors.user_id')->orderBy('created_at','desc')->first();

		$treating_physician_name = $treating_physician ? $treating_physician->first_name.' '.$treating_physician->last_name:'';

		
		$url = url('/').Storage::url('uploads').'/';
        $records = PatientDocument::selectRaw('CONCAT(?, document) as document,id,patient,created_at,type', [$url])->orderBy('created_at','desc')->get();

		$data = array('patient_name'=>$patient_name,'treating_physician'=>$treating_physician_name,'medical_record_number'=>'','documents'=>$records);

		return response()->json(['success'=>true,'message'=>'patient document list','data' => $data]);
	}

	public function document_remove(Request $request, $id){

        $record = PatientDocument::find($id);
        $documentPath = url('/').Storage::url('uploads').'/'.$record->document;
        if (file_exists($documentPath)) {
        	unlink($documentPath);
    	}

		$record->delete();
		return response()->json(['success'=>true,'message'=>'patient document deleted successfully']);
	}

	public function document_download(Request $request, $id){
        $record = PatientDocument::find($id);
        $url = storage_path('app/public/uploads/' . $record->document);
        if (file_exists($url)) {
            return response()->download($url);
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
	}
}