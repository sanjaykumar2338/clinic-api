<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Nurse;
use App\Models\Provider;
use App\Models\User;
use App\Models\DoctorPatient;
use App\Models\Patient;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

class PatientFileController extends Controller
{
    public function index(Request $request)
    {

    }

    public function doctor_patient_list(Request $request)
    {
        if(!$request->id){            
            $doctor_patient = DoctorPatient::get();
            $response = [
                'success'=>true,
                'message'=>'doctor patient list',
                'doctor_patient'=>$doctor_patient
            ];

            return response()->json($response,200);
        }

        $doctor_patient = DoctorPatient::where('expedient_id',$request->id)->get();
        $response = [
                'success'=>true,
                'message'=>'doctor patient',
                'doctor_patient'=>$doctor_patient
            ];

        return response()->json($response,200);
    }

    public function doctor_patient(Request $request, $id){

        $patient_file = DoctorPatient::where('expedient_id',$id)->first();
        if(!$patient_file){
            return response()->json(['message' => 'no patient found','success'=>false], 404);
        }

        $data = [
            'fullname' => $patient_file->first_name.' '.$patient_file->last_name,
            'gender' => $patient_file->gender,
            'dob' => $patient_file->birth_date,
            'education' => $patient_file->education,
            'maritalStatus' => $patient_file->marital_status,
            'occupation' => $patient_file->occupation,
            'placeOrigin' => $patient_file->place_origin,
            'address' => $patient_file->address,
            'allergies' => [
                'hasAllergy' => $patient_file->has_allergy,
                'specificAllergy' => $patient_file->app_allergies,
            ],
            'age' => $patient_file->age,
            'caregiver' => $patient_file->caregiver,
            'vitalSignAssement' => json_decode($patient_file->vital_sign_assement)
        ];

        $response = [
                'success'=>true,
                'message'=>'patient file information',
                'doctor_patient'=>$data
            ];

        return response()->json($response,200);
    }

    public function save(Request $request){

        $patient_file = Patient::where('id',$request->patient_id)->first();
        if(!$patient_file){
            return response()->json(['message' => 'no patient found','success'=>false], 404);
        }

        $patient_file->update([
            'first_name' => $request->fullname,
            'gender' => $request->gender,
            'birth_date' => $request->dob,
            'education' => $request->education,
            'marital_status' => $request->maritalStatus,
            'occupation' => $request->occupation,
            'place_origin' => $request->placeOrigin,
            'address' => $request->address,
            'has_allergy' => $request->hasAllergy,
            'app_allergies' => $request->specificAllergy,
            'age' =>  $request->age,
            'caregiver' => $request->caregiver,
            'vital_sign_assement' => json_encode($request->vitalSignAssement)
        ]);

        $data = [
            'fullname' => $patient_file->first_name.' '.$patient_file->last_name,
            'gender' => $patient_file->gender,
            'dob' => $patient_file->birth_date,
            'education' => $patient_file->birth_date,
            'maritalStatus' => $patient_file->marital_status,
            'occupation' => $patient_file->occupation,
            'placeOrigin' => $patient_file->place_origin,
            'address' => $patient_file->address,
            'allergies' => [
                'hasAllergy' => $patient_file->app_allergies,
                'specificAllergy' => $patient_file->app_allergies,
            ],
            'age' => $patient_file->age,
            'caregiver' => $patient_file->caregiver,
            'vitalSignAssement' => json_decode($patient_file->vital_sign_assement)
        ];

        $response = [
                'success'=>true,
                'message'=>'patient file information saved successfully',
                'doctor_patient'=>$data
            ];

        return response()->json($response,200);
    }
}