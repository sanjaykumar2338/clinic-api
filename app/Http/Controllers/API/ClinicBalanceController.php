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
                'data'=>array('income'=>$revenue,'expenses'=>$expenses)
            ];

        return response()->json($response,200);
    }

    public function income_expenses_statement(){
    	$revenue = Revenue::selectRaw("DATE_FORMAT(created_at, '%b-%y') as month, SUM(price) as price")
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->get();

        $expenses = Expenses::selectRaw("DATE_FORMAT(created_at, '%b-%y') as month, SUM(cost) as cost")
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->get();
        
        $response = [
                'success'=>true,
                'message'=>'income expenses statement data',
                'data'=>array('revenue'=>$revenue,'expenses'=>$expenses)
            ];

        return response()->json($response,200);
    }

    public function all_transcations(){
    	$revenue = Revenue::get();
        $expenses = Expenses::get();
        
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