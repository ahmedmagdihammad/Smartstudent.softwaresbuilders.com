<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\educationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EducationLevelController extends Controller
{
    //
    public function listEducationLevel(){
        // $educationLevel = educationLevel::join('subjects', 'education_levels.subjectId', '=', 'subjects.id')
        //         ->select('education_levels.*', 'subjects.subjectName')
        //         ->get();

        $educationLevel=educationLevel::select('education_levels.*')->get();

                return response()->json([
                    'status' => 'success',
                    'data' => $educationLevel,
                ]);
            
            }

    public function addEducationLevel(Request $req)
    {
        $educationLevel = new educationLevel();
        $educationLevel->nameAr= $req->nameAr;
        $educationLevel->nameEn= $req->nameEn;
        $res=$educationLevel->save();

        if($res)
        {
            return response()->json([
                "Result"=>"Data has been saved"
            ],200);
        }
        else
        {
            return ["Result"=>"Data has not been saved"];
        }
    }

    public function updateEducationLevel($educationId,Request $req)
    {
        $educationLevel=DB::table('education_levels')->where('educationId', $educationId);
        // $educationLevel->update($req->all());

        
        $result=$educationLevel->update([
            'nameAr'=>$req->nameAr,
            'nameEn'=>$req->nameEn
        ]);

        // $res=$educationLevel->save();
                
        if($result)
        {
            return response()->json([
                "Result"=>"Data has been updated"
            ],200);
        }
        else
        {
            return response()->json([
                "the operation failed"
            ],401) ;
        }
    }

    function deleteEducationLevel($educationId)
    {
        $result=DB::table('education_levels')->where('educationId', $educationId)->delete();        
        if($result)
        {
            return response()->json([
                "the education level has been deleted"
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
