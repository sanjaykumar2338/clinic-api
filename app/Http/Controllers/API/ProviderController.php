<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Provider;
use Validator;

class PaymentPurposeController extends Controller
{
    public function index()
    {
        // Fetch all resources
        $resources = Paymentpurpose::where('is_deleted',0)->get();
        $response = [
                'success'=>true,
                'message'=>'payment purpose list',
                'paymentpurpose'=>$resources
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $resource = Paymentpurpose::find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'payment purpose not found'], 404);
        }

        return response()->json(['success'=>true,'paymentpurpose' => $resource]);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255|unique:mcl_payment_method,name'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }


        $resource = Paymentpurpose::create($request->all());
        $response = [
                'success'=>true,
                'message'=>'payment purpose add successfully',
                'paymentpurpose'=>$resource
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $resource = Paymentpurpose::find($id);
        if (!$resource) {
            return response()->json(['message' => 'payment purpose not found','success'=>false], 404);
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
        return response()->json(['paymentpurpose' => $resource,'success'=>true,'message'=>'payment purpose updated successfully']);
    }

    public function destroy($id)
    {
        // Delete a resource
        $resource = Paymentpurpose::find($id);
        if (!$resource) {
            return response()->json(['message' => 'payment purpose  not found','success'=>false], 404);
        }
        $resource->update(['is_deleted'=>1]);
        return response()->json(['message' => 'payment purpose deleted','success'=>true]);
    }
}
