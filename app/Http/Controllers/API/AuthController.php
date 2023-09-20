<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Clinic;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:5',
            'c_password'=>'required|min:5|same:password',
            'user_type' => 'required|in:other,admin,superadmin,user,doctor'
        ]);

        //echo "<pre>"; print_r($request->all()); die;

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $input = $request->all();
        $input['password'] = bcrypt(($input['password']));
        $string = $input['first_name'].'-'.$input['last_name'];
        $input['slug'] = $this->createSlug($string);
        $user = User::create($input);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['user'] = $user;

        $response = [
            'success'=>true,
            'data'=>$success,
            'message'=>'User register successfully!'
        ];

        return response()->json($response,200);
    }
	
	public function check(){
         return response()->json(['message' => 'Unauthorized.','status' => false,'data'=>[]], 401);
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


    public function login(Request $request){

        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();

            $success['token'] = $user->createToken('MyApp')->plainTextToken;            
            $success['user'] = $user;

            $clinic = Clinic::find($user->clinic_id);
            if($clinic){
                $clinic->picture = $clinic->picture ? url('/').Storage::url('uploads').'/'.$clinic->picture : '';
            }

            $success['clinic'] = $clinic;

            $response = [
                'success'=>true,
                'data'=>$success,
                'message'=>'User login successfully!'
            ];

            return response()->json($response,200);
        }else{
            $response = [
                'success'=>false,
                'message'=>'Login failed!'
            ];

            return response()->json($response,401);
        }       
    }
}
