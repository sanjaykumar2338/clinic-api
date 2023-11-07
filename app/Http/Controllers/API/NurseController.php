<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Nurse;
use App\Models\Provider;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NurseController extends Controller
{
    public function index(Request $request)
    {
        $url = url('/').Storage::url('images').'/';
        $nurse = Nurse::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->orderBy('created_at','desc')->get();
        $nurse->each(function ($nurse) {
            $nurse->permissions = unserialize($nurse->permissions);
        });

        $response = [
                'success'=>true,
                'message'=>'nurse list',
                'nurse'=>$nurse,
                'image_path'=>url('/').Storage::url('images')
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $nurse = Nurse::find($id);
        if (!$nurse) {
            return response()->json(['success'=>false,'message' => 'nurse not found'], 404);
        }

        $nurse->permissions = unserialize($nurse->permissions);
        return response()->json(['success'=>true,'nurse' => $nurse,'image_path'=>url('/').Storage::url('images')]);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255',
            'email'=>'required|max:255',
            //'email'=>'required|max:255|unique:mcl_nurse,email',
            'license_number'=>'required',
            'academic_degree'=>'required',
            'password'=>'required',
            'permissions'=>'required',
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

        $exist_user = User::where('email',$request->email)->count();
        $nurse_user = Nurse::where('email',$request->email)->count();

        if($exist_user!=0 || $nurse_user!=0){
            $response = [
                'success'=>false,
                'message'=>'email already exist'
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
        $nurse->permissions = serialize($request->permissions);
        $nurse->signature = $signature;
        $nurse->officialId_front = $officialId_front;
        $nurse->officialId_back = $officialId_back;
        $nurse->clinic_id = $request->user()->clinic_id;
        $nurse->admin_id = $request->user()->id;
        $nurse->save();

        if($nurse){
            $user = new User;
            $user->first_name = $request->name;
            $user->last_name = $request->name;
            $user->user_type = 'nurse';
            $user->email = $request->email;
            $user->clinic_id = $request->user()->clinic_id;
            $user->password = bcrypt(($request->password));
            $user->secure = $request->password;
            $user->nurse_id = $nurse->id;
            $string = $request->name;
            $user->slug = $this->createSlug($string);
            $user->save();

            if($user){
                $nurse->user_id = $user->id;
                $nurse->save();
            }
        }

        $nurse->permissions = unserialize($nurse->permissions);
        $response = [
                'success'=>true,
                'message'=>'nurse add successfully',
                'provider'=>$nurse,
                'image_path'=>url('/').Storage::url('images')
            ];

        return response()->json($response,200);
    }


    public function createSlug($title, $id = 0)
    {
        $slug = Str::slug($title);
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        $i = 1;
        $is_contain = true;
        do {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                $is_contain = false;
                return $newSlug;
            }
            $i++;
        } while ($is_contain);
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return User::select('slug')->where('slug', 'like', $slug.'%')
        ->where('id', '<>', $id)
        ->get();
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
            'email'=>'required|max:255',
            //'email'=>'required|max:255|unique:mcl_nurse,email,'.$id,
            'license_number'=>'required',
            'academic_degree'=>'required',
            'password'=>'',
            'permissions'=>'required',
            'signature'=>'',
            'officialId_front'=>'',
            'officialId_back'=>''
        ]);

        $exist_user = User::where('email',$request->email)->where('id','!=',$nurse->user_id)->count();
        $nurse_user = Nurse::where('email',$request->email)->where('user_id','!=',$nurse->user_id)->count();

        if($exist_user!=0 || $nurse_user!=0){
            $response = [
                'success'=>false,
                'message'=>'email already exist'
            ];

            return response()->json($response,401);
        }

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
        $nurse->permissions = serialize($request->permissions);
        $nurse->signature = $signature;
        $nurse->officialId_front = $officialId_front;
        $nurse->officialId_back = $officialId_back;
        $nurse->clinic_id = $request->user()->clinic_id;
        $nurse->admin_id = $request->user()->id;
        $nurse->save();        

        if($nurse && $nurse->user_id){
            $user = User::find($nurse->user_id);
            $user->first_name = $request->name;
            $user->last_name = $request->name;
            $user->email = $request->email;
            $user->clinic_id = $request->user()->clinic_id;                                  
            $string = $request->name;
            $user->slug = $this->createSlug($string);
            if($request->password){
                $user->secure = $request->password;  
                $user->password = bcrypt(($request->password));
            }

            $user->save();
        }

        $nurse->permissions = unserialize($nurse->permissions);
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
