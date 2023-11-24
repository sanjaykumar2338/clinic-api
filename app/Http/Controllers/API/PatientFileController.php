<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentpurpose;
use App\Models\Nurse;
use App\Models\Provider;
use App\Models\User;
use App\Models\DoctorPatient;
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

        $patient_file = DoctorPatient::where('expedient_id',$id)->join('v3_patients','v3_patients.id','=','v3_doctor_patient.patient_id')->leftjoin('v3_addresses','v3_addresses.patient_id','=','v3_doctor_patient.patient_id')->select('v3_patients.*','v3_addresses.*')->first();

        if(!$patient_file){
            return response()->json(['message' => 'no patient found','success'=>false], 404);
        }

        //echo "<pre>"; print_r($patient_file); die;
        $address = $patient_file->number.' '.$patient_file->colony.' '.$patient_file->street.' '.$patient_file->city.' '.$patient_file->state.' '.$patient_file->country;

        $data = [
            'fullname' => $patient_file->first_name.' '.$patient_file->last_name,
            'gender' => $patient_file->gender,
            'dob' => $patient_file->birth_date,
            'education' => '',
            'maritalStatus' => '',
            'occupation' => $patient_file->occupation,
            'placeOrigin' => $patient_file->city,
            'address' => $address,
            'allergies' => [
                'hasAllergy' => $patient_file->app_allergies !="" ? true:false,
                'specificAllergy' => $patient_file->app_allergies,
            ],
            'age' => '',
            'caregiver' => '',
            'vitalSignAssement' => [
                'bloodPressure' => [
                    ['visit' => 1, 'value' => ''],
                ],
                'heartRate' => [
                    ['visit' => 1, 'value' => ''],
                ],
                'age' => [
                    ['visit' => 1, 'value' => ''],
                ],
                //... (other vital sign assessments)
                'signatures' => [
                    [
                        'nurse' => 'Rocio Vidal Chávez',
                        'date' => '23/11/2023',
                        'time' => '',
                        'signature' => '',
                        'licenseNumber' => '',
                        'academic' => '',
                    ],
                ],
            ],
        ];

        $response = [
                'success'=>true,
                'message'=>'patient file information',
                'doctor_patient'=>$data
            ];

        return response()->json($response,200);
    }
}