<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\Room;
use Validator;

class RoomController extends Controller
{
    // Create a new inventory item
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|unique:mcl_room,name,NULL,id,clinic_id,' .$request->user()->clinic_id. ',is_deleted,0'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        // Create a new inventory item
        $item = Room::create([
            'name' => $request->input('name'),
            'clinic_id' => $request->user()->clinic_id
        ]);

        // Return a response indicating success
        return response()->json(['message' => 'room created', 'data' => $item,'success'=>true]);
    }

    // Read inventory items
    public function index(Request $request)
    {
        // Retrieve all inventory items
        $items = Room::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();

        // Return a response with the inventory items
        return response()->json(['data' => $items,'success'=>true]);
    }

    public function show(Request $request,$id){
    	$resource = Room::find($id);
        if (!$resource) {
            return response()->json(['message' => 'room not found','success'=>false], 404);
        }

        return response()->json(['success'=>true,'data' => $resource]);
    }

    // Read inventory items
    public function list(Request $request)
    {
        // Retrieve all inventory items
        $items = Room::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();

        // Return a response with the inventory items
        return response()->json(['data' => $items,'success'=>true]);
    }

    // Update an inventory item
    public function update(Request $request, $id)
    {
        $resource = Room::find($id);
        if (!$resource) {
            return response()->json(['message' => 'room not found','success'=>false], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(),[
            'name' => 'string|unique:mcl_room,name,'.$id. ',id,clinic_id,' . $request->user()->clinic_id. ',is_deleted,0'
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
        return response()->json(['message' => 'room updated', 'data' => $resource,'success'=>true]);
    }

    // Delete an inventory item
    public function destroy($id)
    {
        $resource = Room::find($id);
        if (!$resource) {
            return response()->json(['message' => 'room not found','success'=>false], 404);
        }

        // Delete the inventory item
        $resource->update(['is_deleted'=>1]);

        // Return a response indicating success
        return response()->json(['message' => 'room deleted','success'=>true]);
    }
}