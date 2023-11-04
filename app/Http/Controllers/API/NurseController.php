<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Nurse;
use App\Models\Provider;
use Validator;
use Illuminate\Support\Facades\Storage;

class NurseController extends Controller
{
    public function index(Request $request)
    {
        $url = url('/').Storage::url('images').'/';
        $nurse = Nurse::where('is_deleted',0)->orderBy('created_at','desc')->get();
        $response = [
                'success'=>true,
                'message'=>'nurse list',
                'nurse'=>$nurse
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
            'name'=>'required|max:255',
            'email'=>'required|max:255|unique:mcl_nurse,email',
            'license_number'=>'required',
            'academic_degree'=>'required',
            'password'=>'required',
            'permissions'=>'required|in:1,2,3,4',
            'signature'=>'required',
            'officialId_front'=>'required',
            'officialId_back'=>'required'
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()->first()
            ];

            return response()->json($response,401);
        }

        $signature = '';
        if($request->signature){
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->signature));
            $storageLocation = 'public/images';
            $signature = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$signature", $imageData);
            $imagePath = "$storageLocation/$signature";
        }

        $officialId_front = '';
        if($request->officialId_front){
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->officialId_front));
            $storageLocation = 'public/images';
            $officialId_front = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$officialId_front", $imageData);
            $imagePath = "$storageLocation/$officialId_front";
        }

        $officialId_back = '';
        if($request->officialId_back){
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->officialId_back));
            $storageLocation = 'public/images';
            $officialId_back = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$officialId_back", $imageData);
            $imagePath = "$storageLocation/$officialId_back";
        }
        
        $nurse = new Nurse;        
        $nurse->name = $request->name;
        $nurse->email = $request->email;
        $nurse->license_number = $request->license_number;
        $nurse->academic_degree = $request->academic_degree;
        $nurse->password = bcrypt(($request->password));
        $nurse->permissions = $request->permissions;
        $nurse->signature = $signature;
        $nurse->officialId_front = $officialId_front;
        $nurse->officialId_back = $officialId_back;
        $nurse->clinic_id = $request->user()->clinic_id;
        $nurse->admin_id = $request->user()->id;
        $nurse->save();

        $response = [
                'success'=>true,
                'message'=>'nurse add successfully',
                'provider'=>$nurse,
                'image_path'=>url('/').Storage::url('images')
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $nurse = Nurse::find($id);
        if (!$nurse) {
            return response()->json(['message' => 'nurse not found','success'=>false], 404);
        }

        // Update an existing resource
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255',
            'email'=>'required|max:255|unique:mcl_nurse,email,'.$id,
            'license_number'=>'required',
            'academic_degree'=>'required',
            'password'=>'required',
            'permissions'=>'required|in:1,2,3,4',
            'signature'=>'',
            'officialId_front'=>'',
            'officialId_back'=>''
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()->first()
            ];

            return response()->json($response,401);
        }

        $signature = $nurse->signature;
        if($request->signature){
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->signature));
            $storageLocation = 'public/images';
            $signature = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$signature", $imageData);
            $imagePath = "$storageLocation/$signature";
        }

        $officialId_front = $nurse->officialId_front;
        if($request->officialId_front){
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->officialId_front));
            $storageLocation = 'public/images';
            $officialId_front = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$officialId_front", $imageData);
            $imagePath = "$storageLocation/$officialId_front";
        }

        $officialId_back = $nurse->officialId_back;
        if($request->officialId_back){
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->officialId_back));
            $storageLocation = 'public/images';
            $officialId_back = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$officialId_back", $imageData);
            $imagePath = "$storageLocation/$officialId_back";
        }

        if($request->password){
            $nurse->password = bcrypt(($request->password));
        }

        $nurse->name = $request->name;
        $nurse->email = $request->email;
        $nurse->license_number = $request->license_number;
        $nurse->academic_degree = $request->academic_degree;        
        $nurse->permissions = $request->permissions;
        $nurse->signature = $signature;
        $nurse->officialId_front = $officialId_front;
        $nurse->officialId_back = $officialId_back;
        $nurse->clinic_id = $request->user()->clinic_id;
        $nurse->admin_id = $request->user()->id;
        $nurse->save();        

        return response()->json(['nurse' => $nurse,'success'=>true,'message'=>'nurse updated successfully','image_path'=>url('/').Storage::url('images')]);
    }

    public function destroy($id)
    {
        // Delete a resource
        $nurse = Nurse::find($id);
        if (!$nurse) {
            return response()->json(['message' => 'nurse not found','success'=>false], 404);
        }
        $nurse->update(['is_deleted'=>1]);
        return response()->json(['message' => 'nurse deleted successfully','success'=>true]);
    }
}
