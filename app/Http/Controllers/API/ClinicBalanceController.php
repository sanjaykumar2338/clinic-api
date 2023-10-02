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
use DB;

class ClinicBalanceController extends Controller
{
	public function summary(Request $request){
        // Fetch all Expenses
        //echo "<pre>"; print_r($request->all()); die;
        $startDate = '';
        $endDate = '';

        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $endDate = Carbon::parse($endDate)->addDay(); 

            $expenses = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('clinic_id',$request->user()->clinic_id)->sum('cost');

            $revenue = Revenue::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('clinic_id',$request->user()->clinic_id)->sum('price');
        }else{

        	$expenses = Expenses::sum('cost');
        	$revenue = Revenue::sum('price');
        }
        
        $chart = $this->chart($request, $startDate, $endDate);
        $response = [
                'success'=>true,
                'message'=>'summary & chart data',
                'data'=>array('summary'=>array('concept'=>$revenue,'expenses'=>$expenses),'chart'=>$chart)
            ];

        return response()->json($response,200);
    }

    public function chart($request, $startDate, $endDate){
        if($startDate && $endDate){
            $endDate = Carbon::parse($endDate)->addDay(); 
            $expenses = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->select('category', DB::raw('sum(cost) as total'))->where('clinic_id',$request->user()->clinic_id)->groupBy('category')->get();

            $revenue = Revenue::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->select('payment_purpose', DB::raw('sum(price) as total'))->where('clinic_id',$request->user()->clinic_id)->groupBy('payment_purpose')->get();
        }else{

            $expenses = Expenses::select('category', DB::raw('sum(cost) as total'))->where('clinic_id',$request->user()->clinic_id)->groupBy('category')->get();
            $revenue = Revenue::select('payment_purpose', DB::raw('sum(price) as total'))->where('clinic_id',$request->user()->clinic_id)->groupBy('payment_purpose')->get();
        }

        $expenses_arr = [];
        if($expenses){
            foreach($expenses as $row){
                $category = Expensescategory::find($row->category);
                $expenses_arr[] = array('category'=>$category->name,'total'=>$row->total);
            }
        }

        $revenue_arr = [];
        if($revenue){
            foreach($revenue as $row){
                $paymentpurpose = Paymentpurpose::find($row->payment_purpose);
                $revenue_arr[] = array('concept'=>$paymentpurpose->name,'total'=>$row->total);
            }
        }

        return array('concept'=>$revenue_arr,'expense'=>$expenses_arr);
    }

    public function income_expenses_statement(Request $request){

         if($request->from && $request->to){
                $startDate = $request->from;
                $endDate = $request->to;
                $endDate = Carbon::parse($endDate)->addDay(); 

    	        $revenue = Revenue::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->selectRaw("DATE_FORMAT(created_at, '%b-%y') as month, payment_purpose, price,created_at")
                 ->groupBy('month','payment_purpose','price','created_at')
                 ->where('clinic_id',$request->user()->clinic_id)
                ->orderByRaw('MIN(created_at)')
                ->get();

                $expenses = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->selectRaw("DATE_FORMAT(created_at, '%b-%y') as month, category, cost, created_at")
                    ->groupBy('month','category','cost','created_at')
                    ->where('clinic_id',$request->user()->clinic_id)
                    ->orderByRaw('MIN(created_at)')
                    ->get();    
            }else{
                $revenue = Revenue::selectRaw("created_at, payment_purpose, price")
                ->where('clinic_id',$request->user()->clinic_id)
                 ->groupBy('created_at','payment_purpose','price')
                ->orderByRaw('MIN(created_at)')
                ->get();

                $expenses = Expenses::selectRaw("created_at, category, cost")
                    ->where('clinic_id',$request->user()->clinic_id)
                    ->groupBy('created_at','category','cost')
                    ->orderByRaw('MIN(created_at)')
                    ->get();  
            }

            $rev = $this->_group_by($revenue, 'payment_purpose');
            $rev_arr = array();
            //echo "<pre>"; print_r($rev); die;

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

                    $months = $this->months();
                    $list = $this->_group_by($inside_arr,'month');
                    //echo "<pre>"; print_r($list); die;

                    $main_data = array();
                    foreach($months as $key=>$month){
                        if(isset($list[$month])){
                            $t = 0;
                            foreach($list[$month] as $row){
                                $t += $row['amount'];
                            }

                            $main_data[] = array('month'=>$row['month'],'year'=>$row['year'],'amount'=>$t);
                        }else{
                            $main_data[] = array('month'=>$month,'year'=>date('Y'),'amount'=>0);
                        }
                    }

                    $rev_arr[] = array('concept'=>$concept,'total'=>$total,'months'=>$main_data);
                }
            }else{
                $months = $this->months();
                $list = $this->_group_by([],'month');
                //echo "<pre>"; print_r($list); die;

                $main_data = array();
                foreach($months as $key=>$month){
                    if(isset($list[$month])){
                        $t = 0;
                        foreach($list[$month] as $row){
                            $t += $row['amount'];
                        }

                        $main_data[] = array('month'=>$row['month'],'year'=>$row['year'],'amount'=>$t);
                    }else{
                        $main_data[] = array('month'=>$month,'year'=>date('Y'),'amount'=>0);
                    }
                }

                $rev_arr[] = array('concept'=>[],'total'=>0,'months'=>$main_data);
            }

            $exp = $this->_group_by($expenses, 'category');
            $exp_arr = array();

            //echo "<pre>"; print_r($exp); die;

            if($exp){
                foreach($exp as $key=>$row){
                    $category = '';

                    if($key){
                        $cate = Expensescategory::find($key);
                        $category = $cate->name;
                    }

                    $total = 0;
                    $inside_arr = array();
                    foreach($row as $item){
                        $total += $item->cost;
                        $month = date("F", strtotime($item->created_at));
                        $year = date("Y", strtotime($item->created_at));

                        $inside_arr[] = array('month'=>$month,'year'=>$year,'amount'=>$item->cost);
                    }

                    $months = $this->months();
                    $list = $this->_group_by($inside_arr,'month');
                    //echo "<pre>"; print_r($list); die;

                    $main_data = array();
                    foreach($months as $key=>$month){
                        if(isset($list[$month])){
                            $t = 0;
                            foreach($list[$month] as $row){
                                $t += $row['amount'];
                            }

                            $main_data[] = array('month'=>$row['month'],'year'=>$row['year'],'amount'=>$t);
                        }else{
                            $main_data[] = array('month'=>$month,'year'=>date('Y'),'amount'=>0);
                        }
                    }

                    $exp_arr[] = array('category'=>$category,'total'=>$total,'months'=>$main_data);
                }
            }else{
                $months = $this->months();
                $list = $this->_group_by([],'month');
                //echo "<pre>"; print_r($list); die;

                $main_data = array();
                foreach($months as $key=>$month){
                    if(isset($list[$month])){
                        $t = 0;
                        foreach($list[$month] as $row){
                            $t += $row['amount'];
                        }

                        $main_data[] = array('month'=>$row['month'],'year'=>$row['year'],'amount'=>$t);
                    }else{
                        $main_data[] = array('month'=>$month,'year'=>date('Y'),'amount'=>0);
                    }
                }

                $exp_arr[] = array('category'=>[],'total'=>0,'months'=>$main_data);
            }
            

            //print_r($this->_group_by($revenue, 'payment_purpose')); die;

        
            $response = [
                    'success'=>true,
                    'message'=>'income expenses statement data',
                    'data'=>array('revenue'=>$rev_arr,'expense'=>$exp_arr)
                ];

            return response()->json($response,200);
    }

    public function months(){
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            // Create a DateTime object for the current month and format it as 'F' to get the full month name
            $date = \DateTime::createFromFormat('!m', $i);
            $monthName = $date->format('F');
            
            // Add the month name to the array
            $months[] = $monthName;
        }

        return $months;
    }

    public function _group_by($array, $key) {
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
                $endDate = Carbon::parse($endDate)->addDay(); 
                
            	$revenue = Revenue::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('clinic_id',$request->user()->clinic_id)->get();
                $expenses = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('clinic_id',$request->user()->clinic_id)->get();
        }else{
                $revenue = Revenue::where('clinic_id',$request->user()->clinic_id)->get();
                $expenses = Expenses::where('clinic_id',$request->user()->clinic_id)->get();
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