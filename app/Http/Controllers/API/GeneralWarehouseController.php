<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Provider;
use App\Models\Material;
use Illuminate\Validation\Rule;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GeneralWarehouseController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all resources
        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $endDate = Carbon::parse($endDate)->addDay(); 

            $material = Material::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('is_deleted',0)->where('stock_type','general')->where('clinic_id',$request->user()->clinic_id)->get();
        }else{
            $material = Material::where('is_deleted',0)->where('stock_type','general')->where('clinic_id',$request->user()->clinic_id)->get();
        }
            
        $response = [
                'success'=>true,
                'message'=>'general warehouse list',
                'material'=>$material
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['success'=>false,'message' => 'general warehouse not found'], 404);
        }

        return response()->json(['success'=>true,'material' => $material]);
    }
    

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'code'=>['required',Rule::unique('mcl_material')->where(function ($query) {
                    return $query->where('stock_type', 'general')->where('clinic_id',$request->user()->clinic_id);
                })],
            'description'=>'required',
            'description_center'=>'required',
            'batch'=>'required',
            'warehouse'=>'required',
            'material_type'=>'required',
            'location'=>'required',
            'available_stock'=>'required|numeric',
            'unit_of_measure'=>'required',
            'entry_date_warehouse'=>'required|date',
            'expiry_date'=>'nullable',
            'cost'=>'required|numeric',
            'stock_type'=>'required|in:general',
            'public_price'=>'required|numeric',
            'stock_type'=>'required'
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
        $material->stock_type = $request->stock_type;
        $material->clinic_id = $request->user()->clinic_id;
        $material->save();

        $response = [
                'success'=>true,
                'message'=>'general warehouse add successfully',
                'material'=>$material
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['success'=>false,'message' => 'general warehouse not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'code'=>'required|unique:mcl_material,code,'.$id,
            'description'=>'required',
            'description_center'=>'required',
            'batch'=>'required',
            'warehouse'=>'required',
            'material_type'=>'required',
            'location'=>'required',
            'available_stock'=>'required|numeric',
            'unit_of_measure'=>'required',
            'entry_date_warehouse'=>'required|date',
            'expiry_date'=>'nullable',
            'cost'=>'required|numeric',
            'public_price'=>'required|numeric',
            'stock_type'=>'required|in:general',
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
        $material->stock_type = $request->stock_type;
        $material->clinic_id = $request->user()->clinic_id;
        $material->save();

        $response = [
                'success'=>true,
                'message'=>'general warehouse update successfully',
                'material'=>$material
            ];

        return response()->json($response,200);
    }

    public function destroy($id)
    {
        // Delete a resource
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['message' => 'general warehouse not found','success'=>false], 404);
        }
        $material->update(['is_deleted'=>1]);
        return response()->json(['message' => 'general warehouse deleted','success'=>true]);
    }

    public function stock(Request $request, $id)
    {
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['success'=>false,'message' => 'general warehouse not found'], 404);
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
                'message'=>'general warehouse update successfully',
                'material'=>$material
            ];

        return response()->json($response,200);
    }

    public function file_import(Request $request){

        $validator = Validator::make($request->all(),[
            'file' => 'required|file|mimes:csv,txt'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $csvData = [];
        $csvFile = fopen($request->file,"r");
        while (($row = fgetcsv($csvFile)) !== false) {
            $csvData[] = $row;
        }

        fclose($csvFile);
        print_r($csvData); die;
        //echo "<pre>"; print_r($request->all()); 

        if(empty($csvData) || count($csvData)==1){
            $response = [
                'success'=>false,
                'message'=>'File empty!'
            ];

            return response()->json($response,401);
        }

        foreach($csvData as $row){
            $material = Material::where('code',$row[0])->first();
            if($material){
                $material->code = $row[0];
                $material->description = $row[1];
                $material->description_center = $row[2];
                $material->batch = $row[3];
                $material->warehouse = $row[4];
                $material->material_type = $row[5];
                $material->location = $row[6];
                $material->available_stock = $row[7];
                $material->unit_of_measure = $row[8];
                $material->entry_date_warehouse = $row[9];
                $material->expiry_date = $row[10];
                $material->cost = $row[11];
                $material->public_price = $row[12];
                $material->stock_type = 'general';
                $material->clinic_id = $request->user()->clinic_id;
                @$material->save();
            }else{
                $material = new Material;
                $material->code = $row[0];
                $material->description = $row[1];
                $material->description_center = $row[2];
                $material->batch = $row[3];
                $material->warehouse = $row[4];
                $material->material_type = $row[5];
                $material->location = $row[6];
                $material->available_stock = $row[7];
                $material->unit_of_measure = $row[8];
                $material->entry_date_warehouse = $row[9];
                $material->expiry_date = $row[10];
                $material->cost = $row[11];
                $material->public_price = $row[12];
                $material->stock_type = 'general';
                $material->clinic_id = $request->user()->clinic_id;
                @$material->save();
            }
        }

        $response = [
            'success'=>true,
            'message'=>'File Imported successfully!'
        ];

        return response()->json($response,200);
    }
}
