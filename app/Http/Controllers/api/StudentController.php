<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\educationLevel;
use Illuminate\Http\Request;
use App\Models\student;
use App\Models\VerifyEmail;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Notifications\WelcomeEmailNotification;
use MyCloudKmsProject\KeyManager;
use App\Http\Controllers\API\BaseController as BaseController;

class StudentController extends Controller
{
    //
    public function listStudent(){

        $student = Student::join('education_levels', 'students.educationId', '=', 'education_levels.educationId')
                ->select('students.*', 'education_levels.*')
                ->get();

        foreach ($student as $s) 
        {
            $s->password = $s->showPassword;
        }


        return response()->json([
            'status' => 'success',
            'data' => $student,
        ]);
    }

    public function addStudent(Request $req)
    {
        $userData = [
            'studentName'    => $req->studentName,
            'phone'    => $req->phone,
            'email'    => $req->email,
            'password' => $req->password,
            // 'ip_address' => $req->ip_address,
        ];

        $rules = [
            'studentName'     =>'required | string',
            'phone'     =>'required|numeric|digits:11',
            'email'    => 'required|email |unique:students,email',
            // 'ip_address'    => 'required',
            'password' => [
                'required',
                'string',
                'min:8',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must co    ntain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ];
    
        $validation = \Validator::make( $userData, $rules );
    
        if ( $validation->fails() ) {
            return $validation->errors()->all();
        }
        
        if(empty($req->ip_address))
        {
            $ip_address = NULL;
        }else{
            $ip_address = $req->ip_address;
        }
        
        $student = new Student;
        $student->studentName= $req->studentName;
        $student->educationId=$req->educationId;
        $student->phone=$req->phone;
        $student->gender=$req->gender;
        $student->location=$req->location;
        $student->email=$req->email;
        $student->ip_address=$ip_address;
        $student->password=bcrypt($req["password"]);
        $student->showPassword=$req["password"];
        $student->code = 'ST' . Str::random(5);
;
        // $path = $req->file('studentImage')->store('public/images');
        // $student->studentImage = $path;
        $res=$student->save();
        
        // $student->notify(new WelcomeEmailNotification($student));

        // Send a verification email to the user's email address
        // Mail::to($student->email)->send(new VerifyEmail($student));

        if($res)
        {
            return  response()->json([
                ["Result"=>"Data has been saved","code"=>$student->code]
            ],200);
        }
        else
        {
            return response()->json([
                "the operation failed"
            ],401);
        }
    }

    public function updateStudent($id,Request $req)
    {
        $students=DB::table('students')->where('studentId', $id);
        $res=$students->update([
            
        "studentName"=> $req->studentName,
        "educationId"=>$req->educationId,
        "phone"=>$req->phone,
        "gender"=>$req->gender,
        "location"=>$req->location,
        "email"=>$req->email,
        "password"=>bcrypt($req["password"]),
        "showPassword"=>$req["password"]

        ]);
        
        if($res)
        {
            return response()->json([
                'Result' => 'Data has been updated',
                "password"=>$req->password
    
            ]);
        }
        else
        {
            return ['Result'=>'operation has been failed'];
        }
        
        if($res)
        {
            return ['Result'=>"Data has been updated"];
        }
        else
        {
            return ['Result'=>'operation has been failed'];
        }
    }
    function deleteStudent($id)
    {
        $result=DB::table('students')->where('studentId', $id)->delete();        
        if($result)
        {
            return response()->json([
                "the Student has been deleted"
            ],200);
        }
        else
        {
            return response()->json([
                "the operation failed"
            ],401);
        }
    }    
    
    public function signinStudent(Request $request)
    {
        $student = Student::where('email', $request->email)->first();
        if (empty($student)) {
            return response()->json([
                'message' => 'Check Your Email',
            ], 401);
        }
        
        if ($student->ip_address == NULL) {
            $student->ip_address = $request->ip_address;
            $student->save();
        }

        if ($student->ip_address != $request->ip_address) 
        {
            return response()->json([
                'message' => 'Signin Another Device',
            ], 401);
        }
            
    
        if (! $student || ! Hash::check($request->password, $student->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }
    
        // $token = $student->createToken('auth_token', ['student'])->plainTextToken;
        $token = $student->createToken('student')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'access_token' => $token,
            'token_type' => 'Bearer',
            "id"=>$student->studentId

        ]);
    }
    
    public function restoreMobile(Request $request)
    {
        $student = Student::where('studentId', $request->studentId )->first();
        if(empty($student->ip_address)){
            return response()->json([
                'message' => 'Not Found Ip Address',
            ], 400);
        }
        
        $student->ip_address = NULL;
        
        if ($student->save()) 
        {
            return response()->json([
                'message' => 'Done Remove Ip Address',
            ], 200);
        }else {
            return response()->json([
                'message' => 'Not Remove Ip Address',
            ], 401);
        }
        
    }
}
