<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\subject;
use Illuminate\Support\Facades\DB;


class SubjectController extends Controller
{
    //
    public function listSubject(){
        $subject = Subject::join('education_levels', 'subjects.educationId', '=', 'education_levels.educationId')
                ->select('subjects.*', 'education_levels.*')
                ->get();        

                return response()->json([
                    'status' => 'success',
                    'data' => $subject,
                ],200);
    }

    public function addSubject(Request $req)
    {
        $subject = new Subject;
        $subject->subjectName= $req->subjectName;
        $subject->educationId=$req->educationId;
        $result=$subject->save();

        if($result)
        {
            return response()->json([
                "Result"=>"Data has been saved"
            ],200);
        }
        else
        {
            return response()->json([
                "the operation failed"
            ],401) ;
        }
    }

    public function updateSubject($id,Request $req)
    {
        $subjects=DB::table('subjects')->where('subjectId', $id);
        $res=$subjects->update([
            "subjectName"=>$req->subjectName,
            "educationId"=>$req->educationId,
        ]);
                
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
            ],401);
        }
    }
    function deleteSubject($subjectId)
    {
        $result=DB::table('subjects')->where('subjectId', $subjectId)->delete();        

        
        if($result)
        {
            return response()->json([
                "the subject has been deleted"
            ],200) ;            
        }

        else
        {
            return response()->json([
                "the operation failed"
            ],401) ;
        }
    }
    
    public function filterSubject($id)
    {
        $result=DB::table('sub_subjects')->where('subjectId',$id)->get();
        return response()->json([
                    'status' => 'success',
                    'data' => $result,
                ],200);
        
    }
    
    public function filterSubSubject($id)
    {
        $result=DB::table('befor_subject_contents')->where('subSubjectId',$id)->orderBy('orderId')->get();
        return response()->json([
                    'status' => 'success',
                    'data' => $result,
                ],200);
        
    }
    
   public function filterBeforSubjectContent($id)
       {
           $result = DB::table('subject_contents')
                  ->where('beforSubjectContentId', $id)
                  ->orderBy('orderId')
                  ->get();
                  
      return response()->json([
                'status' => 'success',
                'data' => $result,
            ], 200);
      }
}
