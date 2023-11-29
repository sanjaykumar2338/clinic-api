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
use App\Models\Doctor;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;

class PatientFileController extends Controller
{
    public function index(Request $request)
    {

    }

    public function doctor_patient_list(Request $request)
    {
        if(!$request->id){            

            $doctor_patient = Doctor::join('mcl_clinic_doctor','mcl_clinic_doctor.doctor','=','v3_doctors.user_id')->join('v3_doctor_patient','v3_doctor_patient.doctor_id','=','v3_doctors.id')->where('mcl_clinic_doctor.clinic_id',$request->user()->clinic_id)->select('v3_doctor_patient.*')->get();

            //$doctor_patient = DoctorPatient::join('mcl_clinic_doctor','mcl_clinic_doctor.doctor','=','v3_doctor_patient.doctor_id')->join('mcl_clinic_doctor','mcl_clinic_doctor.doctor','=','v3_doctor_patient.doctor_id')->where('mcl_clinic_doctor.clinic_id',$request->user()->clinic_id)->select('v3_doctor_patient.*')->get();

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

        $patient_file = DoctorPatient::where('expedient_id',$id)->join('v3_patients','v3_patients.id','=','v3_doctor_patient.patient_id')->select('v3_patients.*','v3_doctor_patient.doctor_id as doctor_ids','v3_doctor_patient.patient_id as patient_ids')->first();
        if(!$patient_file){
            return response()->json(['message' => 'no patient found','success'=>false], 404);
        }
     
        $other = json_decode($patient_file->other_data);
        $data = [
            'id' => $patient_file->id,
            'fullname' => $patient_file->first_name.' '.$patient_file->last_name,
            'gender' => $patient_file->gender,
            'dob' => $patient_file->birth_date,
            'education' => $patient_file->education,
            'maritalStatus' => $patient_file->marital_status,
            'occupation' => $patient_file->occupation,
            'placeOrigin' => $patient_file->place_origin,
            'doctor_id' => $patient_file->doctor_ids,
            'patient_id' => $patient_file->patient_ids,
            'address' => $patient_file->address,
            'allergies' => [
                'hasAllergy' => $patient_file->has_allergy,
                'specificAllergy' => $patient_file->app_allergies,
            ],
            'age' => $patient_file->age,
            'caregiver' => $patient_file->caregiver,
            'location' => $patient_file->location,
            'vitalSignAssement' => json_decode($patient_file->vital_sign_assement),
            'medicalHistory'=>$other->medicalHistory,
            'nutritionControl'=>$other->nutritionControl,
            'chronicDiseases'=>$other->chronicDiseases,
            'visitsConsultations'=>$other->visitsConsultations,
            'diagnosisNursing'=>$other->diagnosisNursing,
            'expectedResults'=>$other->expectedResults,
            'interventionsRecommendations'=>$other->interventionsRecommendations,
            'therapeuticPlan'=>$other->therapeuticPlan,
            'appliedCodes'=>$other->appliedCodes,
            'observations'=>$other->observations,
            'signatures'=>$other->signatures,
            'visitCount'=>$other->visitCount
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

        $doctor = Doctor::find($request->doctor_id);
        if(!$doctor){
            return response()->json(['message' => 'no doctor found','success'=>false], 404);
        }

        $other_data = array('medicalHistory'=>$request->medicalHistory,'nutritionControl'=>$request->nutritionControl,'chronicDiseases'=>$request->chronicDiseases,'visitsConsultations'=>$request->visitsConsultations,'diagnosisNursing'=>$request->diagnosisNursing,'expectedResults'=>$request->expectedResults,'interventionsRecommendations'=>$request->interventionsRecommendations,'therapeuticPlan'=>$request->therapeuticPlan,'appliedCodes'=>$request->appliedCodes,'observations'=>$request->observations,'signatures'=>$request->signatures,'visitCount'=>$request->visitCount);

        $allergies = $request->input('allergies');    
        $hasAllergy = $allergies['hasAllergy']; // true or false
        $specificAllergy = $allergies['specificAllergy']; // "Peanuts" or null if hasAllergy is false

        $patient_file->update([
            'clinic_id' => $request->user()->clinic_id,
            'first_name' => $request->fullname,
            'gender' => $request->gender,
            'birth_date' => $request->dob,
            'education' => $request->education,
            'marital_status' => $request->maritalStatus,
            'occupation' => $request->occupation,
            'place_origin' => $request->placeOrigin,
            'address' => $request->address,
            'doctor' => $request->doctor_id,
            'location' => $request->location,
            'has_allergy' => $hasAllergy,
            'app_allergies' => $specificAllergy,
            'age' =>  $request->age,
            'caregiver' => $request->caregiver,
            'vital_sign_assement' => json_encode($request->vitalSignAssement),
            'other_data' => json_encode($other_data)
        ]);

        $other = json_decode($patient_file->other_data);
        $data = [
            'id' => $patient_file->id,
            'fullname' => $patient_file->first_name.' '.$patient_file->last_name,
            'gender' => $patient_file->gender,
            'dob' => $patient_file->birth_date,
            'education' => $patient_file->birth_date,
            'maritalStatus' => $patient_file->marital_status,
            'occupation' => $patient_file->occupation,
            'placeOrigin' => $patient_file->place_origin,
            'address' => $patient_file->address,
            'location' => $patient_file->location,
            'doctor_id' => $patient_file->doctor,
            'patient_id' => $patient_file->id,
            'allergies' => [
                'hasAllergy' => $patient_file->has_allergy,
                'specificAllergy' => $patient_file->app_allergies,
            ],
            'age' => $patient_file->age,
            'caregiver' => $patient_file->caregiver,            
            'location' => $patient_file->location,
            'vitalSignAssement' => json_decode($patient_file->vital_sign_assement),
            'medicalHistory'=>$other->medicalHistory,
            'nutritionControl'=>$other->nutritionControl,
            'chronicDiseases'=>$other->chronicDiseases,
            'visitsConsultations'=>$other->visitsConsultations,
            'diagnosisNursing'=>$other->diagnosisNursing,
            'expectedResults'=>$other->expectedResults,
            'interventionsRecommendations'=>$other->interventionsRecommendations,
            'therapeuticPlan'=>$other->therapeuticPlan,
            'appliedCodes'=>$other->appliedCodes,
            'observations'=>$other->observations,
            'signatures'=>$other->signatures,
            'visitCount'=>$other->visitCount
        ];

        $response = [
                'success'=>true,
                'message'=>'patient file information saved successfully',
                'doctor_patient'=>$data
            ];

        return response()->json($response,200);
    }

    public function getnursingsheet(Request $request){
        
        //$patient_file = DoctorPatient::where('v3_patients.clinic_id',$request->user()->clinic_id)->join('v3_patients','v3_patients.id','=','v3_doctor_patient.patient_id')->select('v3_patients.*','v3_doctor_patient.expedient_id')->first();

        $patient_files = Patient::where('v3_patients.clinic_id',$request->user()->clinic_id)->join('v3_doctor_patient','v3_doctor_patient.patient_id','=','v3_patients.id')->select('v3_patients.*','v3_doctor_patient.expedient_id')->get();

        if(!$patient_files){
            return response()->json(['message' => 'no patient found','success'=>false], 404);
        }

        $output = array();
        foreach($patient_files as $patient_file){
            $doctor_name = '';
            if($patient_file->doctor){
                $doctor = Doctor::where('v3_doctors.id',$patient_file->doctor)->join('users','users.id','=','v3_doctors.user_id')->first();
                $doctor_name = $doctor->first_name.' '.$doctor->last_name;
            }

            //$data = json_decode($patient_file->vital_sign_assement, true);
            $data = json_decode($patient_file->other_data, true);

            $nurseValue ='';
            if (isset($data['signatures']) && is_array($data['signatures']) && count($data['signatures']) > 0) {
                $lastSignature = @end($data['signatures']); 
                $nurseValue = @$lastSignature['name'];
            }

            // Assuming $yourModel is your model instance or the object containing the data
            $updatedDate = $patient_file->updated_at; // Assuming 'updated_at' is the field name

            $currentDate = Carbon::now();
            $thirtyDaysAgo = Carbon::now()->subDays(30);

            // Check if the updated_at date is within the last 30 days
            $isUpdatedWithin30Days = $updatedDate->greaterThanOrEqualTo($thirtyDaysAgo) && $updatedDate->lessThanOrEqualTo($currentDate);

            // Store the result in a variable
            $result = $isUpdatedWithin30Days ? true : false;

            // You can use $result as needed in your application
            $active = false;
            if ($result) {
                $active = true;
            }

            $output[] = array(
                'expedient_id' => $patient_file->expedient_id,
                'patientName' => $patient_file->first_name.' '.$patient_file->last_name,
                'doctorName' => $doctor_name,
                'location' => $patient_file->location,
                'lastUpdate' => $patient_file->updated_at,
                'updatedNurse' => $nurseValue,
                'active' => $active
            );
        }

        $response = [
                'success'=>true,
                'message'=>'nurshing sheet information',
                'nursingsheet'=>$output
            ];

        return response()->json($response,200);
    }
}