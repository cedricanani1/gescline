<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyseController extends Controller
{
    //
    public function creationAnalyse(Request $request){

        Auth::guard('api')->user();

         $request->validate([
            'code' => 'String|required',
            'denomination' => 'String|required',
            'cotation' => 'String|required',

        ]);

        $code = strtoupper($request->code) ;
        $denomination = $request->denomination;
        $cotation = strtoupper($request->cotation) ;

        $analyse = new Analyse();

        $analyse->code = $code;
        $analyse->denomination = $denomination;
        $analyse->cotation = $cotation;

        $enregistrement = $analyse->save();

        if($enregistrement){
            return response()->json([
                'state' => true,
                'message' => 'Enregistrement éffectué.'
                ]);
        }
        else{
            return response()->json([
                'state' => false,
                'message' => 'Echec de l\'enregistrement. Réessayer'
                ]);
        }
    }

    public function listAnalyses(){
        Auth::guard('api')->user();

        $analyses = Analyse::all();

        if($analyses->isNotEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $analyses]);
        }
        else{
            return response()->json([
                'state'=> false]);
        }

    }
    public function listAnalysesEmpty($clinique_id)
    {
        $analyses = Analyse::whereNotIn('id', function($query){
            $query->select('analyses_id')->from('clinique_analyses');
        })->get();

        if($analyses->isNotEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $analyses]);
        }
        else{
            return response()->json([
                'state'=> false]);
        }

    }

    public function analyse($id){
        Auth::guard('api')->user();

        $analyse = Analyse::where('id',$id)
                            ->first();

        if($analyse){
            return response()->json([
                'state'=> true,
                'data' =>  $analyse]);
        }
        else{
            return response()->json([
                'state'=> false,]);
        }
    }

    public function modifierAnalyse(Request $request, $id){
        Auth::guard('api')->user();
        $analyse = Analyse::findOrFail($id) ;

        $request->validate([
            'code' => 'String|required',
            'denomination' => 'String|required',
            'cotation' => 'String|required',

        ]);

        $code = strtoupper($request->code) ;
        $denomination = $request->denomination;
        $cotation = strtoupper($request->cotation) ;

        $data = [
            'code' =>  $code,
            'denomination' => $denomination,
            'cotation' => $cotation
        ];

        $statut = $analyse->fill($data)->save();

        if($statut){

            return response()->json([
                'state'=> true,
                'message' => 'Opération éffectuée']);
        }
        else{
            return response()->json([
                'state'=> false,
                'message' => 'Opération échouée, réessayer.']);
        }
    }

    public function activerDesactiverAnalyse(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

        $id = $request->id;
        $statut = $request->statut;

        $analyse = Analyse::findOrFail($id) ;

        $data = ['statut' =>  $statut];

        $statutEnregistrement = $analyse->fill($data)->save();

        if($statutEnregistrement){

            return response()->json([
                'state'=> true,
                'message' => 'Opération éffectuée']);
        }
        else{
            return response()->json([
                'state'=> false,
                'message' => 'Opération échouée, réessayer.']);
        }
    }

}
