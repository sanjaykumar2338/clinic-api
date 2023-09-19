<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Provider;
use App\Models\Material;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all resources
        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $material = Material::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('is_deleted',0)->get();
        }else{
            $material = Material::where('is_deleted',0)->get();
        }
            
        $response = [
                'success'=>true,
                'message'=>'material list',
                'material'=>$material
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['success'=>false,'message' => 'material not found'], 404);
        }

        return response()->json(['success'=>true,'material' => $material]);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'code'=>'required|unique:mcl_material',
            'description'=>'required',
            'description_center'=>'required',
            'batch'=>'required',
            'warehouse'=>'required',
            'material_type'=>'required|in:medicine,healing',
            'location'=>'required',
            'available_stock'=>'required|numeric',
            'unit_of_measure'=>'required',
            'entry_date_warehouse'=>'required|date',
            'expiry_date'=>'required|date',
            'cost'=>'required|numeric',
            'public_price'=>'required|numeric'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $material = new Material;
        $material->code = $request->code;
        $material->description = $request->description;
        $material->description_center = $request->description_center;
        $material->batch = $request->batch;
        $material->warehouse = $request->warehouse;
        $material->material_type = $request->material_type;
        $material->location = $request->location;
        $material->available_stock = $request->available_stock;
        $material->unit_of_measure = $request->unit_of_measure;
        $material->entry_date_warehouse = $request->entry_date_warehouse;
        $material->expiry_date = $request->expiry_date;
        $material->cost = $request->cost;
        $material->public_price = $request->public_price;
        $material->save();

        $response = [
                'success'=>true,
                'message'=>'material add successfully',
                'material'=>$material
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['success'=>false,'message' => 'material not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'code'=>'required|unique:mcl_material,code,'.$id,
            'description'=>'required',
            'description_center'=>'required',
            'batch'=>'required',
            'warehouse'=>'required',
            'material_type'=>'required|in:medicine,healing',
            'location'=>'required',
            'available_stock'=>'required|numeric',
            'unit_of_measure'=>'required',
            'entry_date_warehouse'=>'required|date',
            'expiry_date'=>'required|date',
            'cost'=>'required|numeric',
            'public_price'=>'required|numeric'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $material->code = $request->code;
        $material->description = $request->description;
        $material->description_center = $request->description_center;
        $material->batch = $request->batch;
        $material->warehouse = $request->warehouse;
        $material->material_type = $request->material_type;
        $material->location = $request->location;
        $material->available_stock = $request->available_stock;
        $material->unit_of_measure = $request->unit_of_measure;
        $material->entry_date_warehouse = $request->entry_date_warehouse;
        $material->expiry_date = $request->expiry_date;
        $material->cost = $request->cost;
        $material->public_price = $request->public_price;
        $material->save();

        $response = [
                'success'=>true,
                'message'=>'material update successfully',
                'material'=>$material
            ];

        return response()->json($response,200);
    }

    public function destroy($id)
    {
        // Delete a resource
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['message' => 'material not found','success'=>false], 404);
        }
        $material->update(['is_deleted'=>1]);
        return response()->json(['message' => 'material deleted','success'=>true]);
    }

    public function stock(Request $request, $id)
    {
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['success'=>false,'message' => 'material not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'available_stock'=>'required|numeric'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $material->available_stock = $request->available_stock;
        $material->save();

        $response = [
                'success'=>true,
                'message'=>'stock update successfully',
                'material'=>$material
            ];

        return response()->json($response,200);
    }

    public function file_import(Request $request){
        echo "<pre>"; print_r($request->all()); 
    }
}
