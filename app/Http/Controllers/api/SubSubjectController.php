<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\subSubject;
use Illuminate\Support\Facades\DB;


class SubSubjectController extends Controller
{
    //
    public function listSubSubject()
    {
        $subSubject = subSubject::join('subjects','sub_subjects.subjectId','=','subjects.subjectId')
        ->select('sub_subjects.*','subjects.*')
        ->get();

        return response()->json(
            [
                "status"=>"succes",
                "data"=>$subSubject
            ]
        ,200);
    }

    public function addSubSubject(Request $req)
    {
        $subSubject = new subSubject;
        $subSubject->subSubjectName= $req->subSubjectName;
        $subSubject->subjectId=$req->subjectId;
        $res=$subSubject->save();
        if($res)
        {
            return  ["Result"=>"Data has been saved"];
        }
        else
        {
            return ["Result"=>"Data has not been saved"];
        }
    }

    public function updateSubSubject($subSubjectId,Request $req)
    {
        $subSubject=DB::table('sub_subjects')->where('subSubjectId', $subSubjectId);
        $res=$subSubject->update(
            [
                "subSubjectName"=> $req->subSubjectName,
                "subjectId"=>$req->subjectId,
            ]
        );
                
        if($res)
        {
            return ['Result'=>"Data has been updated"];
        }
        else
        {
            return ['Result'=>'operation has been failed'];
        }
    }
    function deleteSubSubject($id)
    {
        $result=DB::table('sub_subjects')->where('subSubjectId', $id)->delete();        
        if($result)
        {
            return response()->json([
                "the course has been deleted"
            ],200) ;            
        }

        else
        {
            return response()->json([
                "the operation failed"
            ],401) ;
        }   
    }


}
