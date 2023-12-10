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

class KardexController extends Controller
{
    public function index(Request $request)
    {
        try{
            $patient = DoctorPatient::where('expedient_id',$request->id)->join('v3_patients','v3_patients.id','=','v3_doctor_patient.patient_id')->select('v3_patients.*','v3_doctor_patient.doctor_id as doctor_ids','v3_doctor_patient.patient_id as patient_ids')->first();

            if(!$patient){
                return response()->json(['message' => 'no patient found','success'=>false], 404);
            }

            $has_allergy = $patient->has_allergy ? true : false;

            $medicines = json_decode(json_encode(array()));
            if($patient->medicines){
                $medicines = json_decode($patient->medicines);
            }

            $nursing_comment = json_decode(json_encode(array()));
            if($patient->nursing_comment){
                $nursing_comment = json_decode($patient->nursing_comment);
            }

            $others = json_decode(json_encode(array()));
            if($patient->others_kardex){
                $others = json_decode($patient->others_kardex);
            }

            $data = [
                'patient_id' => $patient->id,
                'expediente_id' => $request->id,
                'doctor_id' => $patient->doctor,
                'fullname' => $patient->first_name.' '.$patient->last_name,
                'gender' => $patient->gender,
                'dob' => $patient->birth_date,
                'allergies' => [
                    'hasAllergy' => $has_allergy,
                    'specificAllergy' => $patient->app_allergies
                ],
                'diagnosis' => $patient->diagnosis,
                'location' => $patient->location,
                'diet' => $patient->diet,
                'formula' => $patient->formula,
                'medicines' => $medicines,
                'nursingComment' => $nursing_comment,
                'others' => $others
            ];

            
            return response()->json(['success'=>true,'message' => 'patient kardex information!','patient'=>$data],200);
        }catch(\Exceptions $e){
            return response()->json(['message' => $e->getMessage(),'success'=>false], 404);
        }  
    }

    public function save(Request $request, $id){

        try{
            $patient = Patient::findOrFail($id);
            if(!$patient){
                return response()->json(['message' => 'no patient found','success'=>false], 404);
            }

            $data = $request->only([
                'expediente_id', 'doctor_id', 'fullname', 'gender', 'dob', 'allergies','diagnosis','location','diet','formula','medicines','nursingComment','others'
            ]);

            $fieldMapping = [
                'dob' => 'birth_date',
                'nursingComment' => 'nursing_comment',
                'doctor_id'=> 'doctor'
            ];

            // Map field names from request data according to the defined mapping
            $mappedData = collect($data)->mapWithKeys(function ($value, $key) use ($fieldMapping) {
                return [$fieldMapping[$key] ?? $key => $value];
            })->toArray();

            if (isset($mappedData['medicines'])) {
                $mappedData['medicines'] = json_encode($mappedData['medicines']);
            }else{
                $mappedData['medicines'] = json_encode(array());
            }

            if (isset($mappedData['nursing_comment'])) {
                $mappedData['nursing_comment'] = json_encode($mappedData['nursing_comment']);
            }else{
                $mappedData['nursing_comment'] = json_encode(array());
            }

            if (isset($mappedData['others'])) {
                $mappedData['others_kardex'] = json_encode($mappedData['others']);
                unset($mappedData['others']);
            }else{
                $mappedData['others_kardex'] = json_encode(array());
            }

            if (isset($mappedData['allergies']['hasAllergy'])) {
                $mappedData['has_allergy'] = $mappedData['allergies']['hasAllergy'];
            }

            if (isset($mappedData['allergies']['specificAllergy'])) {
                $mappedData['app_allergies'] = $mappedData['allergies']['specificAllergy'];
            }

            if (isset($mappedData['allergies'])){
                unset($mappedData['allergies']);
            }

            if(isset($mappedData['fullname'])) {
                $fullName = $mappedData['fullname'];
                $nameParts = explode(' ', $fullName, 2);
                $mappedData['first_name'] = $nameParts[0];
                if(isset($nameParts[1])) {
                    $mappedData['last_name'] = $nameParts[1];
                } else {
                    $mappedData['last_name'] = '';
                }

                unset($mappedData['fullname']);
            }

            $patient->fill($mappedData);
            $patient->save();

            $has_allergy = $patient->has_allergy ? true : false;

            $medicines = json_decode(json_encode(array()));
            if($patient->medicines){
                $medicines = json_decode($patient->medicines);
            }

            $nursing_comment = json_decode(json_encode(array()));
            if($patient->nursing_comment){
                $nursing_comment = json_decode($patient->nursing_comment);
            }

            $others = json_decode(json_encode(array()));
            if($patient->others_kardex){
                $others = json_decode($patient->others_kardex);
            }

            $data = [
                'patient_id' => $patient->id,
                'expediente_id' => $request->id,
                'doctor_id' => $patient->doctor,
                'fullname' => $patient->first_name.' '.$patient->last_name,
                'gender' => $patient->gender,
                'dob' => $patient->birth_date,
                'allergies' => [
                    'hasAllergy' => $has_allergy,
                    'specificAllergy' => $patient->app_allergies
                ],
                'diagnosis' => $patient->diagnosis,
                'location' => $patient->location,
                'diet' => $patient->diet,
                'formula' => $patient->formula,
                'medicines' => $medicines,
                'nursingComment' => $nursing_comment,
                'others' => $others
            ];    

            return response()->json(['success'=>true,'message' => 'information saved successfully! ','patient'=>$data],200);

        }catch(\Exceptions $e){
            return response()->json(['message' => $e->getMessage(),'success'=>false], 404);
        }        
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
    }
}