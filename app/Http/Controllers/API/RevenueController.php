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
use App\Models\Clinicdoctor;
use App\Models\Revenue;
use App\Models\Clinicadministrator;

class RevenueController extends Controller
{
    public function index()
    {
        // Fetch all revenue
        $resources = Revenue::all();
        $response = [
                'success'=>true,
                'message'=>'revenue method list',
                'revenue'=>$resources
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $resource = Revenue::find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'revenue not found'], 404);
        }

        $response = [
                'success'=>true,
                'message'=>'revenue method list',
                'paymentmethod'=>$resource
            ];

        return response()->json($response,200);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'doctor'=>'required',
            'paitent'=>'required',
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


        $resource = Revenue::create($request->all());
        $response = [
                'success'=>true,
                'message'=>'revenue add successfully',
                'revenue'=>$resource
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $resource = Paymentmethod::find($id);
        if (!$resource) {
            return response()->json(['message' => 'payment method not found','success'=>false], 404);
        }

        // Update an existing resource
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255|unique:mcl_payment_method,name,'.$id
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }
        
        $resource->update($request->all());
        return response()->json(['paymentmethod' => $resource,'success'=>true,'message'=>'payment method updated successfully']);
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
