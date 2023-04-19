<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentSubjectContent;
use App\Models\beforSubjectContent;
use App\Models\subjectContent;
use Illuminate\Support\Facades\DB;



class StudentSubjectContents extends Controller
{
    public function index()
    {
        $studSubConts = StudentSubjectContent::get();
        return response()->json([
            'status' => 'success',
            'data' => $studSubConts,
        ],200);
    }

    public function getStudent($id)
    {
        $studSubConts = StudentSubjectContent::where('studentId', $id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $studSubConts,
        ],200);
    }
    
public function listStudentSubjectContentFlutter($id)
{
    $studentSubjectContent = DB::table('student_subject_contents')->where('studentId', $id)->get();
    foreach ($studentSubjectContent as $t) {
        $t->beforSubjectContentId = array_values(array_unique(json_decode($t->beforSubjectContentId), SORT_REGULAR));
        foreach ($t->beforSubjectContentId as $b) {
            $subjectContents = DB::table('subject_contents')->where('beforSubjectContentId', $b->beforSubjectContentId)->get();
            $b->subjectContentId = array_values(array_unique(json_decode($subjectContents), SORT_REGULAR));
            foreach ($b->subjectContentId as $s) {
                $s->teacherId = array_values(array_unique(json_decode($s->teacherId), SORT_REGULAR));
                $s->subjectContentId = array_values(array_unique(json_decode(DB::table('pdf_subject_contents')->where('subjectContentId', $s->subjectContentId)->get()), SORT_REGULAR));

                $subjects = DB::table('subjects')->where('subjectId', $s->subjectId)->get();
                $s->subjectId = array_values(array_unique(json_decode($subjects), SORT_REGULAR));
                
                foreach ($s->subjectId as $sb) {
                    $sb->educationId = DB::table('education_levels')->where('educationId', $sb->educationId)->first();
                }
            }
        }
    }

    error_log("Student subject contents retrieved: " . print_r($studentSubjectContent, true));

    if ($studentSubjectContent->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No student subject contents found'
        ]);
    } else {
        return response()->json([
            'status' => 'success',
            'data' => $studentSubjectContent,
        ]);
    }


}


public function listStudentSubjectContent($id)
{

// $studentSubjectContent = DB::table('student_subject_contents')
//     ->select([
//         'student_subject_contents.studentSubjectContentId',
//         DB::raw('JSON_ARRAYAGG(JSON_OBJECT("subjectContentId", subject_contents.subjectContentId)) as subjectContentIds'),
//         DB::raw('JSON_ARRAYAGG(JSON_OBJECT("beforSubjectContentId", befor_subject_contents.beforSubjectContentId)) as beforSubjectContentIds'),
//         'students.*'
//     ])
//     ->join('subject_contents', 'student_subject_contents.subjectContentId', 'LIKE', DB::raw('CONCAT("%", subject_contents.subjectContentId, "%")'))
//     ->join('befor_subject_contents', 'student_subject_contents.beforSubjectContentId', 'LIKE', DB::raw('CONCAT("%", befor_subject_contents.beforSubjectContentId, "%")'))
//     ->join('students', 'student_subject_contents.studentId', '=', 'students.studentId')
//     ->where('students.studentId', $id)
//     ->groupBy('student_subject_contents.studentSubjectContentId')
//     ->get();
    
// foreach ($studentSubjectContent as $t) {
//     $t->subjectContentIds = json_decode($t->subjectContentIds);
// }
// foreach ($studentSubjectContent as $t) {
//     $t->beforSubjectContentIds = json_decode($t->beforSubjectContentIds);
// }


//     error_log("Student subject contents retrieved: " . print_r($studentSubjectContent, true));

// if ($studentSubjectContent->isEmpty()) {
//     return response()->json([
//         'status' => 'error',
//         'message' => 'No student subject contents found'
//     ]);
// } else {
//     return response()->json([
//         'status' => 'success',
//         'data' => $studentSubjectContent,
//     ]);
// }

$studentSubjectContent = DB::table('student_subject_contents')
    ->select([
        'student_subject_contents.studentSubjectContentId',
        // DB::raw('JSON_ARRAYAGG(JSON_OBJECT("subjectContentId", subject_contents.subjectContentId,"subjectContentName", subject_contents.subjectContentName,"subjectContentImage", subject_contents.subjectContentImage,"video_url", subject_contents.video_url,"price", subject_contents.price)) as subjectContentIds'),
        DB::raw('JSON_ARRAYAGG(JSON_OBJECT("beforSubjectContentId", befor_subject_contents.beforSubjectContentId,"beforSubjectContentName", befor_subject_contents.beforSubjectContentName)) as beforSubjectContentIds'),
        // DB::raw('JSON_ARRAYAGG(JSON_OBJECT("teacherId", teachers.teacherId,"teacherName",teachers.teacherName)) as teacherIds'),
        'students.*',
    ])
    // ->join('subject_contents', 'student_subject_contents.subjectContentId', 'LIKE', DB::raw('CONCAT("%", subject_contents.subjectContentId, "%")'))
    ->join('befor_subject_contents', 'student_subject_contents.beforSubjectContentId', 'LIKE', DB::raw('CONCAT("%", befor_subject_contents.beforSubjectContentId, "%")'))
    // ->join('teachers', 'teachers.teacherId', 'LIKE', DB::raw('CONCAT("%", teachers.teacherId, "%")'))
    // ->join('students', 'student_subject_contents.studentId', '=', 'students.studentId')
    ->where('students.studentId', $id)
    ->groupBy('student_subject_contents.studentSubjectContentId') // include the teacherId column in the GROUP BY clause
    ->get();

    
// foreach ($studentSubjectContent as $t) {
//     $t->subjectContentIds = json_decode($t->subjectContentIds);
// }
foreach ($studentSubjectContent as $t) {
    $t->beforSubjectContentIds = json_decode($t->beforSubjectContentIds);
}
// foreach ($studentSubjectContent as $t) {
//     $t->teacherIds = json_decode($t->teacherIds);
// }


    error_log("Student subject contents retrieved: " . print_r($studentSubjectContent, true));

if ($studentSubjectContent->isEmpty()) {
    return response()->json([
        'status' => 'error',
        'message' => 'No student subject contents found'
    ]);
} else {
    return response()->json([
        'status' => 'success',
        'data' => $studentSubjectContent,
    ]);
}

}




public function store(Request $request)
{
    $studSubCont = StudentSubjectContent::where('studentId', $request->studentId)->first();
    if (!$studSubCont) {
        error_log("Creating new StudentSubjectContent object for studentId: {$request->studentId}");
        $studSubCont = new StudentSubjectContent();
        $studSubCont->studentId = $request->studentId;
        $studSubCont->beforSubjectContentId = [];
        // $studSubCont->subjectContentId = [];
    }

    $beforSubjectContent = $studSubCont->beforSubjectContentId;
    if (!is_array($beforSubjectContent)) {
        $beforSubjectContent = json_decode($beforSubjectContent, true);
    }
    $ids = BeforSubjectContent::getBeforSubjectContent($request->beforSubjectContentIds);
    $beforSubjectContent = array_merge($beforSubjectContent ?? [], $ids->toArray());
    $studSubCont->beforSubjectContentId = json_encode($beforSubjectContent);

    // $subjectContent = $studSubCont->subjectContentId;
    // if (!is_array($subjectContent)) {
    //     $subjectContent = json_decode($subjectContent, true);
    // }
    // $subjectContentIds = SubjectContent::getSubjectContent($request->SubjectContentIds);
    // $subjectContentIdsArray = $subjectContentIds->toArray();
    // $subjectContent = array_merge($subjectContent ?? [], $subjectContentIdsArray);
    // $studSubCont->subjectContentId = json_encode($subjectContent);

    $res = $studSubCont->save();
    if ($res) {
        error_log("StudentSubjectContent saved for studentId: {$request->studentId}");
        return response()->json([
            "Result" => "Data has been saved"
        ], 200);
    } else {
        error_log("Error saving StudentSubjectContent for studentId: {$request->studentId}");
        return response()->json([
            "there is on updates"
        ], 200);
    }
}

  
    





public function update(Request $request, $id)
{
    $studSubCont = StudentSubjectContent::where('studentId', $id)->first();
    $studSubCont->beforSubjectContentId = [];

    if ($studSubCont) {
        $beforSubjectContent = $studSubCont->beforSubjectContentId;
        if (!is_array($beforSubjectContent)) {
            $beforSubjectContent = json_decode($beforSubjectContent, true);
        }
        $ids = BeforSubjectContent::getBeforSubjectContent($request->beforSubjectContentIds);
        $beforSubjectContent = array_merge($beforSubjectContent ?? [], $ids->toArray());
        $res= $studSubCont->update([
            "beforSubjectContentId"=>json_encode($beforSubjectContent ?? [])
        ]);
        if($res)
        {
            return response()->json([
                "Result"=>"Data has been updated"
            ],200);
        }
        else
        {
            return response()->json([
                "there is no update "
            ],200) ;
        }
    } else {
        return response()->json([
            "Error"=>"No record found with student ID ".$request->studentId
        ], 404);
    }
}



    public function delete($id)
    {
    $studSubCont = StudentSubjectContent::where('studentId', $id)->first();
    $res=$studSubCont->delete();
        if($res)
        {
            return response()->json([
                "Result"=>"Data has been deleted"
            ],200);
        }
        else
        {
            return response()->json([
                "there is no contents for this student"
            ],401) ;
        }
    }
}
