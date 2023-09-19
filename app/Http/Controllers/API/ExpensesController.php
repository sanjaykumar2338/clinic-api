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

class ExpensesController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all Expenses
        //echo "<pre>"; print_r($request->all()); die;
        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $endDate = Carbon::parse($endDate)->addDay(); 
            
            $resources = Expenses::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->with('provider')->with('payment_method')->with('category')->with('patientsingle')->orderBy('created_at','desc')->get();
        }else{
            $resources = Expenses::with('payment_method')->with('provider')->with('category')->with('patientsingle')->orderBy('created_at','desc')->get();
        }

        foreach($resources as $row){      
            $user = Patient::find($row->patient);
            $fullName =  '';
            if ($user) {
                $fullName = $user->first_name.' '.$user->last_name;
            }
            
            $row->patient = ['id'=>$row->patient,'name'=>$fullName];            
        }
        
        $response = [
                'success'=>true,
                'message'=>'expenses list',
                'expenses'=>$resources
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $resource = Expenses::with('payment_method')->with('category')->with('provider')->with('patient')->find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'expenses not found'], 404);
        }

        $response = [
                'success'=>true,
                'message'=>'expenses list',
                'expenses'=>$resource
            ];

        return response()->json($response,200);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'category'=>'required',
            'patient'=>'required',
            'provider'=>'required',
            'cost'=>'required',
            'payment_purpose'=>'required',
            'amount'=>'required',
            'payment_method'=>'required',
            'comments'=>'',
            'quantity'=>'required',
            'paid'=>'required',
            'to_be_paid'=>'required',
            'status'=>'required'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $jsonData = $request->json()->all();
        $expenses = new Expenses;
        $expenses->category = $jsonData['category'];
        $expenses->patient = $jsonData['patient'];
        $expenses->cost = $jsonData['cost'];
        $expenses->amount = $jsonData['amount'];
        $expenses->payment_method = $jsonData['payment_method'];
        $expenses->payment_purpose = $jsonData['payment_purpose'];
        $expenses->comments = $jsonData['comments'];
        $expenses->to_be_paid = $jsonData['to_be_paid'];
        $expenses->status = $jsonData['status'];
        $expenses->quantity = $jsonData['quantity'];
        $expenses->paid = $jsonData['paid'];
        $expenses->provider = $jsonData['provider'];
        $expenses->clinic_id = $request->user()->clinic_id;
        $expenses->save();

        $response = [
                'success'=>true,
                'message'=>'expenses add successfully',
                'expenses'=>$expenses
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $expenses = Expenses::find($id);
        if (!$expenses) {
            return response()->json(['message' => 'expenses not found','success'=>false], 404);
        }

        // Update an existing expenses
        $validator = Validator::make($request->all(),[
            'category'=>'required',
            'patient'=>'required',
            'provider'=>'required',
            'cost'=>'required',
            'payment_purpose'=>'required',
            'amount'=>'required',
            'payment_method'=>'required',
            'comments'=>'',
            'quantity'=>'required',
            'paid'=>'required',
            'to_be_paid'=>'required',
            'status'=>'required'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $jsonData = $request->json()->all();
        $revenue = Expenses::find($id);;
        $expenses->category = $jsonData['category'];
        $expenses->patient = $jsonData['patient'];
        $expenses->cost = $jsonData['cost'];
        $expenses->amount = $jsonData['amount'];
        $expenses->payment_method = $jsonData['payment_method'];
        $expenses->payment_purpose = $jsonData['payment_purpose'];
        $expenses->comments = $jsonData['comments'];
        $expenses->to_be_paid = $jsonData['to_be_paid'];
        $expenses->status = $jsonData['status'];
        $expenses->quantity = $jsonData['quantity'];
        $expenses->paid = $jsonData['paid'];
        $expenses->provider = $jsonData['provider'];
        $expenses->clinic_id = $request->user()->clinic_id;
        $expenses->save();

        return response()->json(['expenses' => $expenses,'success'=>true,'message'=>'expenses updated successfully']);
    }

    public function destroy($id)
    {
        // Delete a resource
        $resource = Expenses::find($id);
        if (!$resource) {
            return response()->json(['message' => 'expenses not found','success'=>false], 404);
        }
        $resource->delete();
        return response()->json(['message' => 'expenses deleted','success'=>true]);
    }
}
