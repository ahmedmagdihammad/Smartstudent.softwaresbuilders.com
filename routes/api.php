<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\StudentController;
use App\Http\Controllers\api\TeacherController;
use App\Http\Controllers\api\SubjectController;
use App\Http\Controllers\api\SubjectContentController;
use App\Http\Controllers\api\SubSubjectController;
use App\Http\Controllers\api\BeforContentSubjectController;
use App\Http\Controllers\api\EducationLevelController;
use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\FatherController;
use App\Http\Controllers\api\StudentSubjectContents;
use App\Http\Controllers\api\PdfSubjectContentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


                    // Route Authentication
Route::post("/register",[AuthenticationController::class,"createUser"]);
Route::post("/signin",[AuthenticationController::class,"signin"]); 

Route::post('/addStudent',[StudentController::class,'addStudent']);
Route::post('/signinStudent',[StudentController::class,'signinStudent']);

Route::middleware('auth:api')->group( function () {
    
    Route::get('/listUsers',[AuthenticationController::class,'listUser']);

                        // StudentRoute
    Route::get('/listStudent',[StudentController::class,'listStudent']);
    Route::post('/updateStudent/{id}',[StudentController::class,'updateStudent']);
    Route::delete('/deleteStudent/{id}',[StudentController::class,'deleteStudent']);
    Route::post('/restoreMobile',[StudentController::class,'restoreMobile']);

                        //Teacher Route 
    Route::get('/listTeacher',[TeacherController::class,'listTeacher']);
    Route::post('/addTeacher',[TeacherController::class,'addTeacher']);
    Route::put('/updateTeacher/{id}',[TeacherController::class,'updateTeacher']);
    Route::delete('/deleteTeacher/{id}',[TeacherController::class,'deleteTeacher']);
    Route::post('/signinTeacher',[TeacherController::class,'signinTeacher']);

                        
                        //Subject Route
    Route::get('/listSubject',[SubjectController::class,'listSubject']);
    Route::post('/addSubject',[SubjectController::class,'addSubject']);
    Route::put('/updateSubject/{id}',[SubjectController::class,'updateSubject']);
    Route::delete('/deleteSubject/{id}',[SubjectController::class,'deleteSubject']);

                        //Route subjectContent
    Route::get('/listSubjectContent',[SubjectContentController::class,'listSubjectContent']);
    Route::post('/addSubjectContent',[SubjectContentController::class,'addSubjectContent']);
    Route::post('/updateSubjectContent/{id}',[SubjectContentController::class,'updateSubjectContent']);
    Route::delete('/deleteSubjectContent/{id}',[SubjectContentController::class,'deleteSubjectContent']);
    Route::post('/arrangeSubjectContent',[SubjectContentController::class,'arrangeSubjectContent']);

                        //Route pdfSubjectContent
    Route::get('/listPdfSubjectContent/{subjectContentId}',[PdfSubjectContentController::class,'listPdfSubjectContent']);
    Route::post('/addPdfSubjectContent',[PdfSubjectContentController::class,'addPdfSubjectContent']);
    Route::post('/updatePdfSubjectContent/{id}',[PdfSubjectContentController::class,'updatePdfSubjectContent']);
    Route::delete('/deletePdfSubjectContent/{id}',[PdfSubjectContentController::class,'deletePdfSubjectContent']);
                                
                                //Route SubSubject 
    Route::get('/listSubSubject',[SubSubjectController::class,'listSubSubject']);
    Route::post('/addSubSubject',[SubSubjectController::class,'addSubSubject']);
    Route::put('/updateSubSubject/{id}',[SubSubjectController::class,'updateSubSubject']);
    Route::delete('/deleteSubSubject/{id}',[SubSubjectController::class,'deleteSubSubject']);
                            

                                //Route EducationLevel 
    Route::get('/listEducationLevel',[EducationLevelController::class,'listEducationLevel']);
    Route::post('/addEducationLevel',[EducationLevelController::class,'addEducationLevel']);
    Route::put('/updateEducationLevel/{id}',[EducationLevelController::class,'updateEducationLevel']);
    Route::delete('/deleteEducationLevel/{id}',[EducationLevelController::class,'deleteEducationLevel']);
                                    
                                    
                                //Route beforSubjectContent 
    Route::get('/listBeforSubjectContent',[BeforContentSubjectController::class,'listBeforSubjectContent']);
    Route::post('/addBeforSubjectContent',[BeforContentSubjectController::class,'addBeforSubjectContent']);
    Route::put('/updateBeforSubjectContent/{id}',[BeforContentSubjectController::class,'updateBeforSubjectContent']);
    Route::delete('/deleteBeforSubjectContent/{id}',[BeforContentSubjectController::class,'deleteBeforSubjectContent']);
    Route::post('/arrangeBeforSubjectContent',[BeforContentSubjectController::class,'arrangeBeforSubjectContent']);

    Route::get('/filterSubject/{id}',[SubjectController::class,'filterSubject']);
    Route::get('/filterSubSubject/{id}',[SubjectController::class,'filterSubSubject']);
    Route::get('/filterBeforSubjectContent/{id}',[SubjectController::class,'filterBeforSubjectContent']);

                                //Route father
    Route::post('/addFather',[FatherController::class,'addFather']);
    Route::get('/listFather',[FatherController::class,'listFather']);
    Route::put('/updateFather/{id}',[FatherController::class,'updateFather']);
    Route::delete('/deleteFather/{id}',[FatherController::class,'deleteFather']);

                                //Route studentSubjectContent
    Route::get('/StudentSubjectContents',[StudentSubjectContents::class,'index']);
    Route::get('/StudentSubjectContents/{id}',[StudentSubjectContents::class,'getStudent']);
    Route::post('/StudentSubjectContents/store',[StudentSubjectContents::class,'store']);
    Route::put('/updateStudentSubjectContents/{id}',[StudentSubjectContents::class,'update']);
    Route::delete('/StudentSubjectContents/delete/{id}',[StudentSubjectContents::class,'delete']);
    Route::get('/listStudentSubjectContents/{id}',[StudentSubjectContents::class,'listStudentSubjectContent']);
    Route::get('/listStudentSubjectContentsFlutter/{id}',[StudentSubjectContents::class,'listStudentSubjectContentFlutter']);

});

// Clear application cache:
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return 'Application cache has been cleared';
});

//Clear route cache:
Route::get('/route-cache', function() {
	Artisan::call('route:cache');
    return 'Routes cache has been cleared';
});

//Clear config cache:
Route::get('/config-cache', function() {
 	Artisan::call('config:cache');
 	return 'Config cache has been cleared';
}); 

// Clear view cache:
Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    return 'View cache has been cleared';
});
                                                    
                            

