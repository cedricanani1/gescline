<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartementController extends Controller
{
    //
    public function creationDepartement(Request $request){

        Auth::guard('api')->user();

         $request->validate([
            'nom' => 'String|required',
            'description' => 'String|nullable',
        ]);

        $nom = ucwords($request->nom) ;
        $description = $request->description;

        $departement = new Departement();

        $departement->nom = $nom;
        $departement->description = $description;

        $enregistrement = $departement->save();

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

    public function listDepartement(){
        Auth::guard('api')->user();

        $departements = Departement::all();

        if(!$departements->isEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $departements]);
        }
        else{
            return response()->json([
                'state'=> false,
                'message' =>  'Aucun departement actif']);
        }


    }

    public function departement($id){
        Auth::guard('api')->user();

        $departement = Departement::where('id',$id)
                            ->first();

        if($departement){
            return response()->json([
                'state'=> true,
                'data' =>  $departement]);
        }
        else{
            return response()->json([
                'state'=> false,]);
        }
    }

    public function modifierDepartement(Request $request, $id){
        Auth::guard('api')->user();
        $departement = Departement::findOrFail($id) ;

        $request->validate([
            'nom' => 'String|required',
            'description' => 'String|nullable',

        ]);

        $nom = ucwords($request->nom) ;
        $description = $request->description;

        $data = [
            'nom' =>  $nom,
            'description' => $description];

        $statut = $departement->fill($data)->save();

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

    public function activerDesactiverDepartement(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

        $id = $request->id;
        $statut = $request->statut;

        $departement = Departement::findOrFail($id) ;

        $data = ['statut' => $statut];

        $statutEnregistrement = $departement->fill($data)->save();

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
