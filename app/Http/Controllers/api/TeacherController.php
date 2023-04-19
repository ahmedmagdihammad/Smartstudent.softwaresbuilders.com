<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\teacher;
use App\Models\educationLevel;
use Illuminate\Support\Facades\DB;


class TeacherController extends Controller
{
    //
    public function listTeacher()
    {
        $teacher = Teacher::get();
        foreach ($teacher as $a) 
        {
            foreach (json_decode($a->educationId) as $e) {
                $a->educationId = educationLevel::where('educationId', $e->educationId)->get();
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $teacher,
        ]);

    }
    public function addTeacher(Request $request)
    {
        $teacher= new teacher();
        $ids= educationLevel::getEducatinsIds( $request->educationIds);
        $idJson = json_encode($ids);
        $teacher->educationId = $idJson;
        $teacher->teacherName = $request->teacherName;
        $teacher->subjectId = $request->subjectId;
        // $teacher->phone=$request->phone;
        // $teacher->gender=$request->gender;
        // $teacher->location=$request->location;
        // $teacher->email=$request->email;
        // $teacher->password=bcrypt($request["password"]);
        
        $res=$teacher->save();

        if($res)
        {
            return response()->json([
                "Data has been updated"
            ],200);
        }
        else
        {
            return response()->json([
                "the operation failed"
            ],401) ;
        }
    }

    public function updateTeacher($teacherId,Request $req)
    {
        $teacher=DB::table('teachers')->where('teacherId', $teacherId);
        $ids= educationLevel::getEducatinsIds($req->educationIds);


        $idJson = json_encode($ids);
       $res= $teacher->update(
        [
            'teacherName'=>$req->teacherName,
            'subjectId'=>$req->subjectId,
            "educationId"=>$idJson

        ]
       );

        
        if($res)
        {
            return response()->json([
                "Data has been updated"
            ],200);
        }
        else
        {
            return response()->json([
                "there is no updates"
            ],200) ;
        }
    }
    function deleteTeacher($id)
    {
        $result=DB::table('teachers')->where('teacherId', $id)->delete();        
        if($result)
        {
            return response()->json([
                "the teacher has been deleted"
            ],200) ;            
        }

        else
        {
            return response()->json([
                "the operation failed"
            ],401) ;
        }
    }
    
        public function signinTeacher(Request $request)
    {

    
        $teacher = Teacher::where('email', $request->email)->first();
    
        if (! $teacher || ! Hash::check($request->password, $teacher->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }
    
        $token = $teacher->createToken('auth_token', ['teacher'])->plainTextToken;
    
        return response()->json([
            'message' => 'Successfully logged in',
            'access_token' => $token,
            'token_type' => 'Bearer',

        ]);
    }
}
