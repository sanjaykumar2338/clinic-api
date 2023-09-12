<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Provider;
use Validator;

class ProviderController extends Controller
{
    public function index()
    {
        // Fetch all resources
        $resources = Provider::where('is_deleted',0)->get();
        $response = [
                'success'=>true,
                'message'=>'provider list',
                'provider'=>$resources
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $resource = Provider::find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'provider not found'], 404);
        }

        return response()->json(['success'=>true,'provider' => $resource]);
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


        $resource = Provider::create($request->all());
        $response = [
                'success'=>true,
                'message'=>'provider add successfully',
                'provider'=>$resource
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $resource = Provider::find($id);
        if (!$resource) {
            return response()->json(['message' => 'provider not found','success'=>false], 404);
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
        return response()->json(['provider' => $resource,'success'=>true,'message'=>'provider updated successfully']);
    }

    public function destroy($id)
    {
        // Delete a resource
        $resource = Provider::find($id);
        if (!$resource) {
            return response()->json(['message' => 'provider not found','success'=>false], 404);
        }
        $resource->update(['is_deleted'=>1]);
        return response()->json(['message' => 'provider deleted','success'=>true]);
    }
}
