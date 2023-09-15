<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Provider;
use Validator;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{
    public function index()
    {
        // Fetch all resources
        $resources = Provider::where('is_deleted',0)->get();
        $response = [
                'success'=>true,
                'message'=>'provider list',
                'provider'=>$resources,
                'image_path'=>url('/').Storage::url('images')
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

        $filename = '';
        if($request->image){
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
            $storageLocation = 'public/images';
            $filename = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$filename", $imageData);
            $imagePath = "$storageLocation/$filename";
        }

        $request->image = $filename;
        $provider = new Provider;
        $provider->name = $request->name;
        $provider->image = $filename;
        $provider->save();

        $response = [
                'success'=>true,
                'message'=>'provider add successfully',
                'provider'=>$provider,
                'image_path'=>url('/').Storage::url('images')
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

        $filename = $resource->image;
        if($request->image){
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
            $storageLocation = 'public/images';
            $filename = uniqid() . '.png'; // You can use any file format you prefer
            Storage::put("$storageLocation/$filename", $imageData);
            $imagePath = "$storageLocation/$filename";
        }
        
        $provider = Provider::find($id);
        $provider->name = $request->name;
        $provider->image = $filename;
        $provider->save();
        
        return response()->json(['provider' => $resource,'success'=>true,'message'=>'provider updated successfully','image_path'=>url('/').Storage::url('images')]);
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
