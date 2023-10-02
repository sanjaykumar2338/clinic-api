<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\Material;
use Validator;

class InventoryController extends Controller
{
    // Create a new inventory item
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:mcl_inventory,name',
            'quantity' => 'required|integer',
            // Add more validation rules as needed
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        // Create a new inventory item
        $item = InventoryItem::create([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
            // Add more fields as needed
        ]);

        // Return a response indicating success
        return response()->json(['message' => 'Inventory item created', 'data' => $item,'success'=>true]);
    }

    // Read inventory items
    public function index(Request $request)
    {
        // Retrieve all inventory items
        $items = Material::where('is_deleted',0)->where('stock_type','general')->where('clinic_id',$request->user()->clinic_id)->select('mcl_material.description as name','mcl_material.available_stock as quantity','id')->get();

        // Return a response with the inventory items
        return response()->json(['data' => $items,'success'=>true]);
    }

    // Update an inventory item
    public function update(Request $request, $id)
    {
        $resource = InventoryItem::find($id);
        if (!$resource) {
            return response()->json(['message' => 'InventoryItem not found','success'=>false], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(),[
            'name' => 'string|unique:mcl_inventory,name,'.$id,
            'quantity' => 'integer',
            // Add more validation rules as needed
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }


        // Update the inventory item
        $resource->update($request->all());

        // Return a response indicating success
        return response()->json(['message' => 'Inventory item updated', 'data' => $resource,'success'=>true]);
    }

    // Delete an inventory item
    public function destroy($id)
    {
        $resource = InventoryItem::find($id);
        if (!$resource) {
            return response()->json(['message' => 'Inventory not found','success'=>false], 404);
        }

        // Delete the inventory item
        $resource->update(['is_deleted'=>1]);

        // Return a response indicating success
        return response()->json(['message' => 'Inventory item deleted','success'=>true]);
    }
}