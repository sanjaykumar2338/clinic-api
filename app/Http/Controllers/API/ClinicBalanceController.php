<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentmethod;
use Validator;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\RevenuePatient;
use App\Models\Clinicdoctor;
use App\Models\Revenue;
use App\Models\Expenses;
use App\Models\Clinicadministrator;
use Carbon\Carbon;

class ClinicBalanceController extends Controller
{
	public function summary(Request $request){
        // Fetch all Expenses
        //echo "<pre>"; print_r($request->all()); die;
        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;

            $expenses = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->sum('cost');

            $revenue = Revenue::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->sum('price');
        }else{

        	$expenses = Expenses::sum('cost');
        	$revenue = Revenue::sum('price');
        }
        
        $response = [
                'success'=>true,
                'message'=>'summary data',
                'expenses'=>$expenses,
                'revenue'=>$revenue
            ];

        return response()->json($response,200);
    }
}