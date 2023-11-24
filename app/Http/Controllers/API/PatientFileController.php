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

        $data = [
            'fullname' => '',
            'gender' => '',
            'dob' => '',
            'education' => '',
            'maritalStatus' => '',
            'occupation' => '',
            'placeOrigin' => '',
            'address' => '',
            'allergies' => [
                'hasAllergy' => '',
                'specificAllergy' => '',
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