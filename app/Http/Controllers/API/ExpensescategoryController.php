<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Expensescategory;
use Validator;

class ExpensescategoryController extends Controller
{
    public function index()
    {
        // Fetch all resources
        $resources = Expensescategory::where('is_deleted',0)->get();
        $response = [
                'success'=>true,
                'message'=>'expenses category list',
                'paymentpurpose'=>$resources
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $resource = Expensescategory::where('is_deleted',0)->find($id);
        if (!$resource) {
            return response()->json(['success'=>false,'message' => 'expenses category not found'], 404);
        }

        return response()->json(['success'=>true,'expensescategory' => $resource]);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255|unique:mcl_expense_category,name'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }


        $resource = Expensescategory::create($request->all());
        $response = [
                'success'=>true,
                'message'=>'expenses category add successfully',
                'expensescategory'=>$resource
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $resource = Expensescategory::find($id);
        if (!$resource) {
            return response()->json(['message' => 'expenses category not found','success'=>false], 404);
        }

        // Update an existing resource
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255|unique:mcl_expense_categorys,name,'.$id
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }
        
        $resource->update($request->all());
        return response()->json(['expensescategory' => $resource,'success'=>true,'message'=>'expenses category updated successfully']);
    }

    public function destroy($id)
    {
        // Delete a resource
        $resource = Expensescategory::find($id);
        if (!$resource) {
            return response()->json(['message' => 'expenses category not found','success'=>false], 404);
        }
        $resource->update(['is_deleted'=>1]);
        return response()->json(['message' => 'expenses category deleted','success'=>true]);
    }
}
