<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\father;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class FatherController extends Controller
{
    //
        public function listFather(){


        $father=father::select('fathers.*')->get();

                return response()->json([
                    'status' => 'success',
                    'data' => $father,
                ]);
            
            }
    public function addFather(Request $req)
    {
        $father = new father();
        $father->fatherName= $req->fatherName;
        $father->phone=$req->phone;
        $father->gender=$req->gender;
        $father->location=$req->location;
        $father->email=$req->email;
        $father->password=bcrypt($req["password"]);
        $father->code = 'ST' . Str::random(5);


        $res=$father->save();

        if($res)
        {
            return  response()->json([
                ["Result"=>"Data has been saved","code"=>$father->code]
            ],200);
        }
        else
        {
            return response()->json([
                "the operation failed"
            ],401);
        }
    }
    
        public function updateFather($id,Request $req)
    {
        
        
        $father=DB::table('fathers')->where('fatherId', $id);
        // dd($father)
;        
        $result=$father->update([
        "fatherName"=> $req->fatherName,
        "phone"=>$req->phone,
        "gender"=>$req->gender,
        "location"=>$req->location,
        "email"=>$req->email,
        "password"=>bcrypt($req["password"]),
        // code = 'ST' . Str::random(5);
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

    function deleteFather($fatherId)
    {
        $result=DB::table('fathers')->where('fatherId', $fatherId)->delete();        
        if($result)
        {
            return response()->json([
                "the father has been deleted"
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
