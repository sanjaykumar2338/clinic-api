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
                'medicines' => json_decode($patient->medicines),
                'nursingComment' => json_decode($patient->nursing_comment),
                'others' => json_decode($patient->others_kardex)
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

            if (isset($data['medicines'])) {
                $mappedData['medicines'] = json_encode($mappedData['medicines']);
            }

            if (isset($data['nursing_comment'])) {
                $mappedData['nursingComment'] = json_encode($mappedData['nursingComment']);
            }

            if (isset($data['others'])) {
                $mappedData['others_kardex'] = json_encode($mappedData['others']);
                unset($mappedData['others']);
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
            return response()->json(['success'=>true,'message' => 'information saved successfully! ','patient'=>$data],200);

        }catch(\Exceptions $e){
            return response()->json(['message' => $e->getMessage(),'success'=>false], 404);
        }        
    }
}