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
use App\Models\RevenuePatient;
use App\Models\Clinicadministrator;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DB;

class PatientBalanceController extends Controller
{

	public function index(Request $request){
		//echo "<pre>"; print_r($request->user()->clinic_id); die;
		$res = RevenuePatient::join('mcl_revenue','mcl_revenue.id','=','mcl_revenue_patient.revenue')->join('v3_patients','v3_patients.id','=','mcl_revenue_patient.patient')->join('users','users.id','=','v3_patients.user_id')->join('v3_doctors','v3_doctors.id','=','mcl_revenue.doctor')->join('users as doctor_data','doctor_data.id','=','v3_doctors.user_id')->where('mcl_revenue.clinic_id',$request->user()->clinic_id)->selectRaw('CONCAT(users.first_name, " ", users.last_name) as name, mcl_revenue_patient.id, mcl_revenue.price as total_amount,mcl_revenue.amount_paid as paid_amount,mcl_revenue.price - mcl_revenue.amount_paid as pending_amount,CONCAT(doctor_data.first_name, " ", doctor_data.last_name) as doctor,mcl_revenue_patient.patient')->orderBy('mcl_revenue_patient.id','desc')->get();

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

            	$revenue = Revenue::join('mcl_revenue_patient','mcl_revenue_patient.id','=','mcl_revenue.revenue')->whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('mcl_revenue_patient.patient',$id)->get();

                $expenses = Expenses::whereBetween('mcl_revenue.created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('patient',$id)->get();
        }else{
                $revenue = Revenue::join('mcl_revenue_patient','mcl_revenue_patient.id','=','mcl_revenue.revenue')->where('mcl_revenue_patient.patient',$id)->get();

                $expenses = Expenses::where('patient',$id)->get();
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
                'message'=>'income expenses all all transcations',
                'data'=>$arr
            ];

        return response()->json($response,200);
	}
}