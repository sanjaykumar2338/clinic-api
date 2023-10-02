<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentmethod;
use Validator;
use DB;

class PaymentMethodController extends Controller
{
    public function index()
    {
        // Fetch all resources
        $resources = Paymentmethod::where('is_deleted', 0)
            ->select(DB::raw('CONCAT(UCASE(LEFT(name, 1)), SUBSTRING(name, 2)) as name'), 'id')
            ->get();
        $response = [
                'success'=>true,
                'message'=>'payment method list',
                'paymentmethod'=>$resources
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $resource = Paymentmethod::find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'payment method not found'], 404);
        }

        return response()->json(['success'=>true,'paymentmethod' => $resource]);
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


        $resource = Paymentmethod::create($request->all());
        $response = [
                'success'=>true,
                'message'=>'payment method add successfully',
                'paymentmethod'=>$resource
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
        $resource = Paymentmethod::find($id);
        if (!$resource) {
            return response()->json(['message' => 'payment method  not found','success'=>false], 404);
        }
        $resource->update(['is_deleted'=>1]);
        return response()->json(['message' => 'payment method deleted','success'=>true]);
    }
}
