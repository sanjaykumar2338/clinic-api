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
use App\Models\Clinicadministrator;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all revenue
        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $resources = Revenue::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->with('payment_purpose')->with('payment_method')->with('inventory')->with('doctorsingle')->with('patient')->get();
        }else{
            $resources = Revenue::with('payment_purpose')->with('payment_method')->with('inventory')->with('doctorsingle')->with('patient')->get();
        }

        foreach($resources as $row){      
            $user = Patient::find($row->patient);
            $fullName =  '';
            if ($user) {
                $fullName = $user->first_name.' '.$user->last_name;
            }
            $row->patient = ['id'=>$row->patient,'name'=>$fullName];

            if ($doctor) {
                $row->doctor = ['id'=>$row->doctorsingle->id,'name'=>$doctor->first_name.' '.$doctor->last_name];
            }     

            $row->doctor = '';            
            $doctor = User::find($row->doctorsingle->user_id);
            if ($doctor) {
                $row->doctor = ['id'=>$row->doctorsingle->id,'name'=>$doctor->first_name.' '.$doctor->last_name];
            }            
        }

        $response = [
                'success'=>true,
                'message'=>'revenue list',
                'revenue'=>$resources
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $resource = Revenue::with('paymentpurpose')->with('paymentmethod')->with('inventory')->with('doctor')->with('patient')->find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'revenue not found'], 404);
        }

        $response = [
                'success'=>true,
                'message'=>'revenue list',
                'revenue'=>$resource
            ];

        return response()->json($response,200);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'doctor'=>'required',
            'patient'=>'required',
            'payment_purpose'=>'required',
            'price'=>'required',
            'amount_paid'=>'required',
            'payment_method'=>'required',
            'comments'=>'required',
            'inventory'=>'',
            'quantity'=>''
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $jsonData = $request->json()->all();
        $revenue = new Revenue;
        $revenue->doctor = $jsonData['doctor'];
        $revenue->price = $jsonData['price'];
        $revenue->amount_paid = $jsonData['amount_paid'];
        $revenue->payment_method = $jsonData['payment_method'];
        $revenue->payment_purpose = $jsonData['payment_purpose'];
        $revenue->comments = $jsonData['comments'];
        $revenue->patient = $jsonData['patient'];
        $revenue->inventory = isset($jsonData['inventory']) ? $jsonData['inventory']:null;
        $revenue->quantity = isset($jsonData['quantity']) ? $jsonData['quantity']:null;
        $revenue->clinic_id = $request->user()->clinic_id;
        $revenue->save();

        /*
        if($revenue->id && $jsonData['patient']){
            foreach($jsonData['patient'] as $row){
                $doctor = new RevenuePatient;
                $doctor->revenue = $revenue->id;
                $doctor->patient = $row['id'];
                $doctor->save();
            }
        }
        */

        $response = [
                'success'=>true,
                'message'=>'revenue add successfully',
                'revenue'=>$revenue
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $resource = Revenue::find($id);
        if (!$resource) {
            return response()->json(['message' => 'revenue not found','success'=>false], 404);
        }

        // Update an existing resource
        $validator = Validator::make($request->all(),[
            'doctor'=>'required',
            'patient'=>'required',
            'payment_purpose'=>'required',
            'price'=>'required',
            'amount_paid'=>'required',
            'payment_method'=>'required',
            'comments'=>'required',
            'inventory'=>'',
            'quantity'=>''
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }


        $jsonData = $request->json()->all();
        $revenue = Revenue::find($id);;
        $revenue->doctor = $jsonData['doctor'];
        $revenue->price = $jsonData['price'];
        $revenue->amount_paid = $jsonData['amount_paid'];
        $revenue->payment_method = $jsonData['payment_method'];
        $revenue->comments = $jsonData['comments'];
        $revenue->inventory = $jsonData['inventory'];
        $revenue->quantity = $jsonData['quantity'];
        $revenue->clinic_id = $request->user()->clinic_id;
        $revenue->save();

        if($revenue->id && $jsonData['patient']){            
            RevenuePatient::where('revenue',$revenue->id)->delete();
            foreach($jsonData['patient'] as $row){
                $doctor = new RevenuePatient;
                $doctor->revenue = $revenue->id;
                $doctor->patient = $row['id'];
                $doctor->save();
            }
        }
        
        return response()->json(['revenue' => $resource,'success'=>true,'message'=>'revenue updated successfully']);
    }

    public function destroy($id)
    {
        // Delete a resource
        $resource = Revenue::find($id);
        if (!$resource) {
            return response()->json(['message' => 'revenue not found','success'=>false], 404);
        }
        $resource->delete();
        return response()->json(['message' => 'revenue deleted','success'=>true]);
    }
}
