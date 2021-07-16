<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Clinique;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Clinique_analyse;
use App\Models\Clinique_assurance;
use App\Models\Clinique_departement;
use Illuminate\Support\Facades\Auth;
use App\Models\Clinique_departement_service;

class CliniqueController extends Controller
{
    //
    public function creationClinique(Request $request){

        Auth::guard('api')->user();

         $request->validate([
            'numero_identifiant' => 'String|required',
            'nom' => 'String|required',
            'email' => 'email|nullable',
            'telephone' => 'String|required',
            'telephone_urgence' => 'String|required',
            'adresse_physique' => 'String|required',
            'adresse_postale' => 'String|nullable',
            'fax' => 'String|nullable',
            'departements_services' => 'required'
        ]);

        $numero_identifiant = $request->numero_identifiant;
        $nom = strtoupper($request->nom);
        $email = $request->email;
        $telephone = $request->telephone;
        $telephone_urgence = $request->telephone_urgence;
        $adresse_physique = ucwords($request->adresse_physique);
        $adresse_postale = $request->adresse_postale;
        $fax = $request->fax;
        $departements_services = $request->departements_services;

        $clinique = new Clinique();

        $clinique->numero_identifiant = $numero_identifiant;
        $clinique->nom = $nom;
        $clinique->email = $email;
        $clinique->telephone = $telephone;
        $clinique->telephone_urgence = $telephone_urgence;
        $clinique->adresse_physique = $adresse_physique;
        $clinique->adresse_postale = $adresse_postale;
        $clinique->fax = $fax;

        $enregistrement = $clinique->save();

        if($enregistrement){
            $dataEnregistrement1 = [];
            $dataEnregistrement2 = [];

            foreach ($departements_services as $departement_service) {

                $id_departement = $departement_service['id_departement'];
                $id_services = $departement_service['id_services'];

                foreach ($id_services as $id_service) {


                    $clinique_departement_service = new Clinique_departement_service();

                    $clinique_departement_service->clinique_id = $clinique->id;
                    $clinique_departement_service->departement_id = $id_departement;
                    $clinique_departement_service->service_id = $id_service['id'];

                    $enregistrement_clinique_departement_service = $clinique_departement_service->save();
                    // array_push($dataEnregistrement1, $clinique_departement_service);

                }
               // array_push($dataEnregistrement2, $dataEnregistrement1);

            }

            return response()->json([
                'state' => true,
                //'enregistement' => $dataEnregistrement2,
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

    public function listClinique(){
        Auth::guard('api')->user();

        $cliniques = Clinique::all();


        if(!$cliniques->isEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $cliniques]);
        }
        else{
            return response()->json([
                'state'=> false,
                ]);
        }
    }

    public function clinique($id){
        $dataReturn = [];
        Auth::guard('api')->user();

        $clinique = Clinique::where('id',$id)
                            ->first();

        if($clinique){


            $departements =  Clinique_departement_service::select('departements.id as departement_id'
            ,'departements.nom as departement_nom','departements.statut as departement_statut')
                            //'services.id as service_id', 'services.nom as service_nom', 'clinique_departement_services.statut as statut')
                            ->join('cliniques', 'cliniques.id', 'clinique_departement_services.clinique_id')
                            ->join('departements', 'departements.id', 'clinique_departement_services.departement_id')
                            //->join('services', 'services.id', 'clinique_departement_services.service_id')
                            ->where('clinique_departement_services.clinique_id', $clinique->id)
                            ->distinct()
                            ->get();

            if($departements->isNotEmpty()){
                foreach($departements as $departement){

                    $services =  Clinique_departement_service::select('services.id as service_id',
                     'services.nom as service_nom','services.statut as service_statut')
                            ->join('cliniques', 'cliniques.id', 'clinique_departement_services.clinique_id')
                            ->join('departements', 'departements.id', 'clinique_departement_services.departement_id')
                            ->join('services', 'services.id', 'clinique_departement_services.service_id')
                            ->where('clinique_departement_services.clinique_id', $clinique->id)
                            ->where('departements.id', $departement->departement_id)
                            ->get();
                    if($services->isNotEmpty()){
                        $tmp = [
                            'departement_id' => $departement->departement_id,
                            'departement_nom' =>  $departement->departement_nom,
                            'services' => $services,
                            'departement_statut' =>  $departement->departement_statut,
                        ];
                        array_push($dataReturn, $tmp );
                    }

                }
                return response()->json([
                    'state'=> true,
                    'data' =>  $clinique,
                    'departements' => $dataReturn]);
            }
        }
        else{
            return response()->json([
                'state'=> false,]);
        }
    }

    public function modifierClinique(Request $request, $id){
        Auth::guard('api')->user();
        $clinique = Clinique::findOrFail($id) ;

         $request->validate([
            'numero_identifiant' => 'String|required',
            'nom' => 'String|required',
            'email' => 'email|nullable',
            'telephone' => 'String|required',
            'telephone_urgence' => 'String|required',
            'adresse_physique' => 'String|required',
            'adresse_postale' => 'String|nullable',
            'fax' => 'String|nullable'
           // 'departements_services' => 'required'
        ]);
        $numero_identifiant = $request->numero_identifiant;
        $nom = strtoupper($request->nom);
        $email = $request->email;
        $telephone = $request->telephone;
        $telephone_urgence = $request->telephone_urgence;
        $adresse_physique = ucwords($request->adresse_physique);
        $adresse_postale = $request->adresse_postale;
        $fax = $request->fax;
        //$departements_services = $request->departements_services;

        $data = [
            'numero_identifiant' =>  $numero_identifiant,
            'nom' =>  $nom,
            'email' => $email,
            'telephone' => $telephone,
            'telephone_urgence' => $telephone_urgence,
            'adresse_physique' => $adresse_physique,
            'adresse_postale' => $adresse_postale,
            'fax' =>  $fax];

        $statut = $clinique->fill($data)->save();

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

    public function activerDesactiverClinique(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

        $id = $request->id;
        $statut = $request->statut;

        $clinique = Clinique::findOrFail($id) ;

        $data = ['statut' =>  $statut];

        $statutEnregistrement = $clinique->fill($data)->save();

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

    public function getDepartementsClinique($clinique)
    {
        $departements =  Clinique_departement_service::select('departements.id as departement_id'
            ,'departements.nom as departement_nom','departements.statut as departement_statut')
                            //'services.id as service_id', 'services.nom as service_nom', 'clinique_departement_services.statut as statut')
                            ->join('cliniques', 'cliniques.id', 'clinique_departement_services.clinique_id')
                            ->join('departements', 'departements.id', 'clinique_departement_services.departement_id')
                            //->join('services', 'services.id', 'clinique_departement_services.service_id')
                            ->where('clinique_departement_services.clinique_id', $clinique)
                            ->distinct()
                            ->get();

        return response()->json([
            'data'=> $departements
        ]);
    }

    public function listeServicesDepartement($clinique_id, $departement_id){
        Auth::guard('api')->user();
        $servicesDepartement = Clinique_departement_service::select('services.id as service_id',
        'services.nom as service_nom','services.statut as service_statut')
               ->join('cliniques', 'cliniques.id', 'clinique_departement_services.clinique_id')
               ->join('departements', 'departements.id', 'clinique_departement_services.departement_id')
               ->join('services', 'services.id', 'clinique_departement_services.service_id')
               ->where('clinique_departement_services.clinique_id', $clinique_id)
               ->where('departements.id', $departement_id)
               ->get();

        if(!$servicesDepartement->isEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $servicesDepartement]);
        }
        else{
            return response()->json([
                'state'=> false,
                'data' =>  $servicesDepartement]);
        }
    }

    public function getDepartementsNotInClinique($clinique){
        Auth::guard('api')->user();
        $departementsInClinique =  Clinique_departement_service::select('departements.id as departement_id')
                            //'services.id as service_id', 'services.nom as service_nom', 'clinique_departement_services.statut as statut')
                            ->join('cliniques', 'cliniques.id', 'clinique_departement_services.clinique_id')
                            ->join('departements', 'departements.id', 'clinique_departement_services.departement_id')
                            //->join('services', 'services.id', 'clinique_departement_services.service_id')
                            ->where('clinique_departement_services.clinique_id', $clinique)
                            ->distinct()
                            ->get();

        $departementsNotInClinique = Departement::select('id','nom','statut')
                        ->whereNotIn('id',$departementsInClinique)
                        ->get();

        if(!$departementsNotInClinique->isEmpty()){

             $services = Service::all();

             return response()->json([
                'state' => true,
                'departements' => ['state' => true,'data'=> $departementsNotInClinique],
                'services' => ['state' => true,'data'=> $services]
            ]);

        }
        else{
            return response()->json([
                'state' => false,
            ]);
        }

    }


    public function getServicesNotInDepartement($departement_id){
        Auth::guard('api')->user();
        $servicesInClinique =  Clinique_departement_service::select('services.id as service_id')
                            //'services.id as service_id', 'services.nom as service_nom', 'clinique_departement_services.statut as statut')
                            //->join('cliniques', 'cliniques.id', 'clinique_departement_services.clinique_id')
                            ->join('departements', 'departements.id', 'clinique_departement_services.departement_id')
                            ->join('services', 'services.id', 'clinique_departement_services.service_id')
                            ->where('clinique_departement_services.departement_id', $departement_id)
                            ->distinct()
                            ->get();

        $servicesNotInClinique = Service::select('id','nom','statut')
                        ->whereNotIn('id',$servicesInClinique)
                        ->get();

        if(!$servicesNotInClinique->isEmpty()){

            return response()->json([
                'state' => true,
                'data'=> $servicesNotInClinique
            ]);
        }
        else{
            return response()->json([
                'state' => false,
            ]);
        }

    }


    public function updateToDeleteDepartementClinique(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'departement_id' => 'required'
        ]);
        //$numero_identifiant = $request->numero_identifiant;

        $clinique_id = $request->clinique_id;
        $departement_id = $request->departement_id;

        $cliniqueDepartements = Clinique_departement_service::select('id')
                            ->where('clinique_id',$clinique_id)
                            ->where('departement_id',$departement_id)
                            ->get();
        $result = [];
        foreach($cliniqueDepartements as $cliniqueDepartement){

            $resultDelete = Clinique_departement_service::findOrFail($cliniqueDepartement->id)->delete();

            array_push($result,$resultDelete);
        }

        return response()->json([
            'result' => $result,
        ]);


    }

    public function  updateToDeleteDepartementCliniqueService(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'departement_id' => 'required',
            'service_id' => 'required'
        ]);
        //$numero_identifiant = $request->numero_identifiant;

        $clinique_id = $request->clinique_id;
        $departement_id = $request->departement_id;
        $service_id = $request->service_id;

        $cliniqueDepartementService = Clinique_departement_service::select('id')
                            ->where('clinique_id',$clinique_id)
                            ->where('departement_id',$departement_id)
                            ->where('service_id',$service_id)
                            ->first();

        $resultDelete = Clinique_departement_service::findOrFail($cliniqueDepartementService->id)->delete();

        return response()->json([
            'result' => $resultDelete,
        ]);


    }


    public function addCliniqueDepartementService(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'departements_services' => 'required'
        ]);
        $clinique_id = $request->clinique_id;
        $departements_services = $request->departements_services;
        //return $service_id;
       //return "OK";
        foreach ($departements_services as $departement_service) {
            $id_departement = $departement_service['id_departement'];
            $services_id = $departement_service["id_services"];
            //return $id_departement;

            foreach ($services_id as $id_service) {

                //return $id_service;
                $clinique_departement_service = new Clinique_departement_service();

                $clinique_departement_service->clinique_id = $clinique_id;
                $clinique_departement_service->departement_id = $id_departement;
                $clinique_departement_service->service_id = $id_service['id'];
                 $clinique_departement_service->save();

            }
           // array_push($dataEnregistrement2, $dataEnregistrement1);

        }
        return response()->json([
            'state' => true,
            //'enregistement' => $dataEnregistrement2,
            'message' => 'Enregistrement éffectué.'
            ]);

    }


    public function attributionAssurancesClinique(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'assurances' => 'required'
        ]);

        $clinique_id = $request->clinique_id;
        $assurances = $request->assurances;

        foreach($assurances as $assurance){
            $clinique_assurance = new Clinique_assurance();

            $clinique_assurance->clinique_id = $clinique_id;
            $clinique_assurance->assurance_id = $assurance['id'];

            $clinique_assurance->save();
        }
        return response()->json([
            'state' => true,
            //'enregistement' => $dataEnregistrement2,
            'message' => 'Enregistrement éffectué.'
            ]);

    }

    public function attributionAnalysesClinique(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'analyses' => 'required'
        ]);

        $clinique_id = $request->clinique_id;
        $analyses = $request->analyses;

        foreach($analyses as $analyse){
            $clinique_analyse = new Clinique_analyse();

            $clinique_analyse->clinique_id = $clinique_id;
            $clinique_analyse->analyses_id = $analyse['id'];

            $clinique_analyse->save();
        }
        return response()->json([
            'state' => true,
            'message' => 'Enregistrement éffectué.'
            ]);


    }


}
