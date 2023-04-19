<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PdfSubjectContent;
use App\Models\subjectContent;
use Illuminate\Support\Facades\File;

class PdfSubjectContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPdfSubjectContent($id)
    {
        $checkPdfSubjectContents = subjectContent::find($id);
        if (empty($checkPdfSubjectContents)) {
            return response()->json([
                "Not Student"
            ],401);
        }

        $pdfSubjectContents = subjectContent::find($id)->getPdfSubjectContents;
        return response()->json([
            'status' => 'success',
            'data' => $pdfSubjectContents,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPdfSubjectContent(Request $request)
    {   
        $imageName = time().'.'.$request->pdf->extension();
        $request->pdf->move(public_path('pdfs'), $imageName);

        $pdfSubjectContent = new PdfSubjectContent;
        $pdfSubjectContent->subjectContentId = $request->subjectContentId;
        $pdfSubjectContent->namePdf = $request->namePdf;
        $pdfSubjectContent->pdfs = 'pdfs/'.$imageName;

        if ($pdfSubjectContent->save()) 
        {
            return response()->json([
                'status' => 'success',
                'data' => $pdfSubjectContent,
            ],200);
        }
        else
        {
            return response()->json([
                "the operation failed"
            ],401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePdfSubjectContent(Request $request, $id)
    {
        $pdfSubjectContent = PdfSubjectContent::find($id);

        $imageName = '';
        if ($request->hasFile('pdf')) {
            $imageName = time().'.'.$request->pdf->extension();
            $request->pdf->move(public_path('pdfs'), $imageName);

            if ($pdfSubjectContent->pdfs) {
                File::delete(public_path('pdfs'), $pdfSubjectContent->pdfs);
            }
        } else {
            $imagePath = $pdfSubjectContent->pdfs;
        }

        $pdfSubjectContent->subjectContentId = $request->subjectContentId;
        $pdfSubjectContent->namePdf = $request->namePdf;
        $pdfSubjectContent->pdfs = 'pdfs/'.$imageName;

        if ($pdfSubjectContent->save()) 
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePdfSubjectContent($id)
    {
        if(PdfSubjectContent::find($id)->delete())
        {
            return response()->json([
                "the pdf content has been deleted"
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
