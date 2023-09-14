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
use App\Models\Paymentpurpose;
use App\Models\Expensescategory;
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

    public function income_expenses_statement(Request $request){

         if($request->from && $request->to){
                $startDate = $request->from;
                $endDate = $request->to;

    	       $revenue = Revenue::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->selectRaw("DATE_FORMAT(created_at, '%b-%y') as month, payment_purpose, price")
                 ->groupBy('month','payment_purpose','price')
                ->orderByRaw('MIN(created_at)')
                ->get();

                $expenses = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->selectRaw("DATE_FORMAT(created_at, '%b-%y') as month, category, cost")
                    ->groupBy('month','category')
                    ->orderByRaw('MIN(created_at)')
                    ->get();    
            }else{
                $revenue = Revenue::selectRaw("created_at, payment_purpose, price")
                 ->groupBy('created_at','payment_purpose','price')
                ->orderByRaw('MIN(created_at)')
                ->get();

                $expenses = Expenses::selectRaw("created_at, category, cost")
                    ->groupBy('created_at','category','cost')
                    ->orderByRaw('MIN(created_at)')
                    ->get();  
            }

            $rev = $this->_group_by($revenue, 'payment_purpose');
            $rev_arr = array();

            if($rev){
                foreach($rev as $key=>$row){
                    $concept = '';

                    if($key){
                        $payment_purpose = Paymentpurpose::find($key);
                        $concept = $payment_purpose->name;
                    }

                    $total = 0;
                    $inside_arr = array();
                    foreach($row as $item){
                        $total += $item->price;
                        $month = date("F", strtotime($item->created_at));
                        $year = date("Y", strtotime($item->created_at));

                        $inside_arr[] = array('month'=>$month,'year'=>$year,'amount'=>$item->price);
                    }

                    $rev_arr[] = array('concept'=>$concept,'total'=>$total,'months'=>$inside_arr);
                }
            }

            $exp = $this->_group_by($expenses, 'category');
            $exp_arr = array();

            if($exp){
                foreach($exp as $key=>$row){
                    $category = '';

                    if($key){
                        $cate = Expensescategory::find($key);
                        $category = $cate->name;
                    }

                    $total = 0;
                    $exp_arr = array();
                    foreach($row as $item){
                        $total += $item->cost;
                        $month = date("F", strtotime($item->created_at));
                        $year = date("Y", strtotime($item->created_at));
                        $inside_arr[] = array('month'=>$month,'year'=>$year,'amount'=>$item->cost,'total'=>$total);
                    }

                    $exp_arr[] = array('category'=>$category,'total'=>$total,'months'=>$inside_arr);
                }
            }
            

            //print_r($this->_group_by($revenue, 'payment_purpose')); die;

        
            $response = [
                    'success'=>true,
                    'message'=>'income expenses statement data',
                    'data'=>array('revenue'=>$rev_arr,'expense'=>$exp_arr)
                ];

            return response()->json($response,200);
    }

    function _group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    public function all_transcations(Request $request){

        if($request->from && $request->to){
                $startDate = $request->from;
                $endDate = $request->to;

            	$revenue = Revenue::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->get();
                $expenses = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->get();
        }else{
                $revenue = Revenue::get();
                $expenses = Expenses::get();
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