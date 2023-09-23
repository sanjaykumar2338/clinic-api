<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Provider;
use App\Models\BillingDetails;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BillingDetailsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all resources
        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $endDate = Carbon::parse($endDate)->addDay(); 

            $resources = BillingDetails::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('clinic_id',$request->user()->clinic_id)->where('is_deleted',0)->get();
        }else{
            $resources = BillingDetails::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();
        }
            
        $response = [
                'success'=>true,
                'message'=>'Billing Details list',
                'billing_details'=>$resources
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $resource = BillingDetails::find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'Billing Detail not found'], 404);
        }

        return response()->json(['success'=>true,'billing_details' => $resource]);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'rfc'=>'required',
            'use_of_invoice'=>'required',
            'fiscal_regime'=>'required',
            'email'=>'required|email',
            'email_2'=>'required|email',
            'email_3'=>'required|email',
            'postal_code'=>'required|numeric'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $resource = new BillingDetails;
        $resource->name = $request->name;
        $resource->rfc = $request->rfc;
        $resource->use_of_invoice = $request->use_of_invoice;
        $resource->fiscal_regime = $request->fiscal_regime;
        $resource->email = $request->email;
        $resource->email_2 = $request->email_2;
        $resource->email_3 = $request->email_3;
        $resource->postal_code = $request->postal_code;
        $resource->clinic_id = $request->user()->clinic_id;
        $resource->save();

        $response = [
                'success'=>true,
                'message'=>'Billing Detail add successfully',
                'billing_details'=>$resource
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $resource = BillingDetails::find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'Billing Detail not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'rfc'=>'required',
            'use_of_invoice'=>'required',
            'fiscal_regime'=>'required',
            'email'=>'required',
            'email_2'=>'required',
            'email_3'=>'required',
            'postal_code'=>'required|numeric'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $resource->name = $request->name;
        $resource->rfc = $request->rfc;
        $resource->use_of_invoice = $request->use_of_invoice;
        $resource->fiscal_regime = $request->fiscal_regime;
        $resource->email = $request->email;
        $resource->email_2 = $request->email_2;
        $resource->email_3 = $request->email_3;
        $resource->postal_code = $request->postal_code;
        $resource->clinic_id = $request->user()->clinic_id;
        $resource->save();

        $response = [
                'success'=>true,
                'message'=>'Billing Detail update successfully',
                'material'=>$resource
            ];

        return response()->json($response,200);
    }

    public function destroy($id)
    {
        // Delete a resource
        $resource = BillingDetails::find($id);
        if (!$resource) {
            return response()->json(['message' => 'Billing Detail not found','success'=>false], 404);
        }
        $resource->update(['is_deleted'=>1]);
        return response()->json(['message' => 'Billing Detail deleted','success'=>true]);
    }
}
