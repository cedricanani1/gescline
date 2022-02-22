<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssuranceController extends Controller
{
    //
    public function creationAssurance(Request $request){

        Auth::guard('api')->user();

         $request->validate([
            'nom' => 'String|required',
            'entreprise' => 'String|required',
            'pourcentage' => 'integer|required'

        ]);

        $nom = strtoupper($request->nom) ;
        $entreprise = strtoupper($request->entreprise) ;
        $assurance = new Assurance();
        $assurance->nom = $nom;
        $assurance->entreprise = $entreprise;
        // $assurance->pourcentage = $request->pourcentage;


        $enregistrement = $assurance->save();

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

    public function listAssurances(){
        Auth::guard('api')->user();

        $assurances = Assurance::with('dossiers')->get();
        foreach ($assurances as $key => $assurance) {
            foreach ($assurance->dossiers as $key => $dossier) {
                $dossier->client;
                $dossier->factures;
            }
        }
        if(!$assurances->isEmpty()){
            return response()->json([
                'state'=> true,
                'data'=> $assurances]);
        }
        else{
            return response()->json([
                'state'=> false]);
        }

    }
    public function listAssurancesEmpty($clinique_id)
    {
        Auth::guard('api')->user();

        $assurances = Assurance::where('id', function($query){
                        $query->select('assurance_id')->from('clinique_assurances');
                    })->get();

        if(!$assurances->isEmpty()){
            return response()->json([
                'state'=> true,
                'data'=> $assurances]);
        }
        else{
            return response()->json([
                'state'=> false]);
        }

    }


    public function assurance($id){
        Auth::guard('api')->user();

        $assurance = Assurance::where('id',$id)
                            ->first();

        if($assurance){
            return response()->json([
                'state'=> true,
                'data' =>  $assurance]);
        }
        else{
            return response()->json([
                'state'=> false,]);
        }
    }

    public function modifierAssurance(Request $request, $id){
        Auth::guard('api')->user();
        $assurance = Assurance::findOrFail($id) ;
        $request->validate([
            'nom' => 'String|required',
            'entreprise' => 'String|required'

        ]);

        $nom = strtoupper($request->nom) ;
        $entreprise = strtoupper($request->entreprise) ;

        $data = [
            'nom' =>  $nom,
            'entreprise' => $entreprise,
            'pourcentage' => $request->pourcentage
        ];

        $statut = $assurance->fill($data)->save();

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

    public function activerDesactiverAssurance(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

        $id = $request->id;
        $statut = $request->statut;

        $assurance = Assurance::findOrFail($id) ;

        $data = ['statut' =>  $statut];

        $statutEnregistrement = $assurance->fill($data)->save();

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
