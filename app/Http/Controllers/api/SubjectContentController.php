<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\subject;
use App\Models\subjectContent;
use App\Models\teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectContentController extends Controller
{
    public function listSubjectContent()
    {
        $subjectContents = subjectContent::get();
        foreach ($subjectContents as $s) {
            foreach (json_decode($s->teacherId) as $t) {
                $s->teacherId = Teacher::where('teacherId', $t->teacherId)->get();
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $subjectContents,
        ]); 

     }

    public function addSubjectContent(Request $req)
    {
        $subjectContent = new subjectContent;

        $ids= teacher::getTeacherIds($req->teacherIds);
        $idJson = json_encode($ids);
        $subjectContent->teacherId = $idJson;
        $subjectContent->beforSubjectContentId	=$req->beforSubjectContentId;
        $subjectContent->subSubjectId	=$req->subSubjectId;
        $subjectContent->subjectId	=$req->subjectId;

        $subjectContent->subjectContentName= $req->subjectContentName;
        $subjectContent->price=$req->price;
        $subjectContent->video_url=$req->video_url;
        $subjectContent->description=$req->description;
        $imagePath = $req->file('subjectContentImage')->store('public/images');
        $subjectContent->subjectContentImage = $imagePath;

        // $imageName = time().'.'.$req->image->extension();  
        // $req->image->move(public_path('images'), $imageName);
        
        // $filePath = $req->file('file')->store('public/files');
        // $subjectContent->file = $filePath;
        $res=$subjectContent->save();
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

    public function updateSubjectContent($subjectContentId,Request $req)
    {
        $subjectContent = subjectContent::where('subjectContentId', $subjectContentId)->first();
        if (!empty($req->subjectContentimage)) {
            $validated = $req->validate([
                'subjectContentimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $imageName = time().'.'.$req->subjectContentimage->extension();
            $imagePath = $req->subjectContentimage->move(public_path('images'), $imageName);

        }else {
            $imagePath = $subjectContent->subjectContentimage;
        }

        $ids= teacher::getTeacherIds($req->teacherIds);
        $idJson = json_encode($ids);
        $subjectContent->teacherId = $idJson;
        $subjectContent->subSubjectId = $req->subSubjectId;
        $subjectContent->beforSubjectContentId = $req->beforSubjectContentId;
        $subjectContent->subjectContentName = $req->subjectContentName;
        $subjectContent->price = $req->price;
        $subjectContent->video_url = $req->video_url;
        $subjectContent->description = $req->description;
        $subjectContent->subjectContentimage = $imagePath;
        $res= $subjectContent->save();

        if($res)
        {
            return response()->json([
                "Data has been saved"
            ],200);   
        }
        else
        {
         return response()->json([
                "there is no updates"
            ],200);
        }
    }
    
    function deleteSubjectContent($id)
    {
        $result=DB::table('subject_contents')->where('subjectContentId', $id)->delete();        

        if($result)
        {
            return response()->json([
                "the subject content has been deleted"
            ],200) ;            
        }

        else
        {
            return response()->json([
                "the operation failed"
            ],401) ;
        }
    }
    
    public function arrangeSubjectContent(Request $request)
    {
            $arrLength = count($request->toArray());

            for ($x = 0; $x < $arrLength; $x++) {
                $subjectContent = SubjectContent::where('subjectContentId', $request[$x])->first();
                $subjectContent->orderId = $x+1;
                $subjectContent->save();
                }
    }
}
