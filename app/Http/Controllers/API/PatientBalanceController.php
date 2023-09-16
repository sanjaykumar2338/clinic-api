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
		$res = RevenuePatient::join('mcl_revenue','mcl_revenue.id','=','mcl_revenue_patient.revenue')->join('v3_patients','v3_patients.id','=','mcl_revenue_patient.patient')->join('users','users.id','=','v3_patients.user_id')->join('v3_doctors','v3_doctors.id','=','mcl_revenue.doctor')->join('users as doctor_data','doctor_data.id','=','v3_doctors.user_id')->where('mcl_revenue.clinic_id',$request->user()->clinic_id)->selectRaw('CONCAT(users.first_name, " ", users.last_name) as name, mcl_revenue_patient.id, mcl_revenue.price as total_amount,mcl_revenue.amount_paid as paid_amount,mcl_revenue.price - mcl_revenue.amount_paid as pending_amount,CONCAT(doctor_data.first_name, " ", doctor_data.last_name) as doctor')->orderBy('mcl_revenue_patient.id','desc')->get();

		//echo "<pre>"; print_r($res); die;
		return response()->json(['success'=>true,'message'=>'patient balance list','patientbalance' => $res]);
	}
}