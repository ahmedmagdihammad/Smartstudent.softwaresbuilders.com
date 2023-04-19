<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\beforSubjectContent;
use App\Models\subSubject;
use Illuminate\Support\Facades\DB;

class BeforContentSubjectController extends Controller
{
    //
    public function listBeforSubjectContent(){
        $beforSubjectContent = beforSubjectContent::get();
        foreach ($beforSubjectContent as $b) {
            $b->subSubjectId = subSubject::where('subSubjectId', $b->subSubjectId)->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $beforSubjectContent,

        ]);   
    }

    public function addBeforSubjectContent(Request $req)
    {
        $beforSubjectContent=new beforSubjectContent ();
        $beforSubjectContent->beforSubjectContentName=$req->beforSubjectContentName;
        $beforSubjectContent->subSubjectId=$req->subSubjectId;
        $res= $beforSubjectContent->save();
        if($res)
        {
            return response()->json([
                "Data has been saved"
            ],200);
        }
        else
        {
            return response()->json([
                "the operation failed"
            ],401);
        }
    }

    public function updateBeforSubjectContent($id,Request $req)
    {
        $beforSubjectContent=DB::table('befor_subject_contents')->where('beforSubjectContentId',$id);
        $res=$beforSubjectContent->update([
            "beforSubjectContentName"=>$req->beforSubjectContentName,
            "subSubjectId"=>$req->subSubjectId
            
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
     public function deleteBeforSubjectContent($id)
    {
        $res=DB::table('befor_subject_contents')->where('beforSubjectContentId',$id)->delete();
        if($res)
        {
            return response()->json([
                "Data has been deleted"
            ],200);  
        }
        else{
            return response()->json([
                "the operation failed"
            ],401);
        }
    }
    
public function arrangeBeforSubjectContent(Request $request)
{
    $ids = $request->toArray();

    // Use a single query to update the order of all matching records
    DB::table('befor_subject_contents')
        ->whereIn('beforSubjectContentId', $ids)
        ->update(['orderId' => DB::raw('FIND_IN_SET(beforSubjectContentId, "' . implode(',', $ids) . '")')]);

    // Return a success response
    return response()->json(['status' => 'success']);
}


}
