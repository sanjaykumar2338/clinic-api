<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Provider;
use App\Models\Campaign;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all resources
        if($request->from && $request->to){
            $startDate = $request->from;
            $endDate = $request->to;
            $endDate = Carbon::parse($endDate)->addDay(); 

            $campaign = Campaign::whereBetween('created_at',[Carbon::parse($startDate)->format('Y-m-d 00:00:00'),Carbon::parse($endDate)->format('Y-m-d 23:59:59')])->where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();
        }else{
            $campaign = Material::where('is_deleted',0)->where('clinic_id',$request->user()->clinic_id)->get();
        }
            
        $response = [
                'success'=>true,
                'message'=>'campaign list',
                'campaign'=>$campaign
            ];

        return response()->json($response,200);
    }

    public function show($id)
    {
        // Fetch a single resource by ID
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['success'=>false,'message' => 'campaign not found'], 404);
        }

        return response()->json(['success'=>true,'campaign' => $campaign]);
    }

    public function store(Request $request)
    {
        // Create a new resource
        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:mcl_campaign',
            'subject'=>'required',
            'date'=>'required',
            'schedule'=>'required',
            'message'=>'required',
            'header_image'=>'',
            'main_image'=>'',
            'final_image'=>'',
            'header_image_url'=>'',
            'main_image_url'=>'',
            'final_image_url'=>''
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $campaign = new Campaign;
        $campaign->name = $request->name;
        $campaign->subject = $request->subject;
        $campaign->date = $request->date;
        $campaign->schedule = $request->schedule;
        $campaign->message = $request->message;
        $campaign->header_image_url = $request->header_image_url;
        $campaign->main_image_url = $request->main_image_url;
        $campaign->final_image_url = $request->final_image_url;
        $campaign->clinic_id = $request->user()->clinic_id;

        $header_image = '';
        if ($request->hasFile('header_image')) {
            $file = $request->file('header_image');
            $header_image = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/campaign', $filename, 'public');
            $campaign->header_image = $header_image;
        }

        $main_image = '';
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $main_image = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/campaign', $filename, 'public');
            $campaign->main_image = $main_image;
        }

        $final_image = '';
        if ($request->hasFile('final_image')) {
            $file = $request->file('final_image');
            $final_image = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/campaign', $filename, 'public');
            $campaign->final_image = $final_image;
        }

        $campaign->save();
        $response = [
                'success'=>true,
                'message'=>'campaign add successfully',
                'material'=>$campaign
            ];

        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['success'=>false,'message' => 'campaign not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:mcl_campaign,name,'.$id,
            'subject'=>'required',
            'date'=>'required',
            'schedule'=>'required',
            'message'=>'required',
            'header_image'=>'',
            'main_image'=>'',
            'final_image'=>'',
            'header_image_url'=>'',
            'main_image_url'=>'',
            'final_image_url'=>''
        ]);

        if($validator->fails()){
            $response = [
                'success'=>false,
                'message'=>$validator->errors()
            ];

            return response()->json($response,401);
        }

        $campaign->name = $request->name;
        $campaign->subject = $request->subject;
        $campaign->date = $request->date;
        $campaign->schedule = $request->schedule;
        $campaign->message = $request->message;
        $campaign->header_image_url = $request->header_image_url;
        $campaign->main_image_url = $request->main_image_url;
        $campaign->final_image_url = $request->final_image_url;
        $campaign->clinic_id = $request->user()->clinic_id;

        if ($request->hasFile('header_image')) {
            $file = $request->file('header_image');
            $header_image = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/campaign', $filename, 'public');
            $campaign->header_image = $header_image;
        }

        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $main_image = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/campaign', $filename, 'public');
            $campaign->main_image = $main_image;
        }

        if ($request->hasFile('final_image')) {
            $file = $request->file('final_image');
            $final_image = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/campaign', $filename, 'public');
            $campaign->final_image = $final_image;
        }



        $campaign->save();
        $response = [
                'success'=>true,
                'message'=>'campaign update successfully',
                'campaign'=>$campaign
            ];

        return response()->json($response,200);
    }

    public function destroy($id)
    {
        // Delete a resource
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['message' => 'campaign not found','success'=>false], 404);
        }
        $campaign->update(['is_deleted'=>1]);
        return response()->json(['message' => 'campaign deleted','success'=>true]);
    }
}
