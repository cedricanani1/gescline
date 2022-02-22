<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\Departement_service;
use App\Models\Clinique_departement;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    public function creationService(Request $request){

        Auth::guard('api')->user();

         $request->validate([
            'nom' => 'String|required',
            'description' => 'String|nullable',

        ]);

        $nom = ucwords($request->nom);
        $description = $request->description;

        $service = new Service();

        $service->nom = $nom;
        $service->description = $description;

        $enregistrement = $service->save();

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

    public function listService(){
        Auth::guard('api')->user();

        $services = Service::all();

        if(!$services->isEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $services]);
        }
        else{
            return response()->json([
                'state'=> false]);
        }

    }

    public function service($id){
        Auth::guard('api')->user();

        $service = Service::where('id',$id)
                            ->first();

        if($service){
            return response()->json([
                'state'=> true,
                'data' =>  $service]);
        }
        else{
            return response()->json([
                'state'=> false,]);
        }
    }

    public function modifierService(Request $request, $id){
        Auth::guard('api')->user();
        $service = Service::findOrFail($id) ;

        $request->validate([
            'nom' => 'String|required',
            'description' => 'String|nullable',

        ]);

        $nom = ucwords($request->nom) ;
        $description = $request->description;

        $data = [
            'nom' =>  $nom,
            'description' => $description];

        $statut = $service->fill($data)->save();

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

    public function activerDesactiverService(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

        $id = $request->id;
        $statut = $request->statut;

        $service = Service::findOrFail($id) ;

        $data = ['statut' =>  $statut];

        $statutEnregistrement = $service->fill($data)->save();

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
