<?php

namespace App\Http\Controllers;

use App\Models\Clinique;
use App\Models\Clinique_analyse;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\Lieu_de_travail;
use App\Models\Departement_service;
use App\Models\Clinique_departement;
use Illuminate\Support\Facades\Auth;

class Attribution extends Controller
{
    //
    public function departementsClinique(Request $request){

        $donneesEnregistrements = array();
        $i = 0;

        Auth::guard('api')->user();

        $request->validate([
            'id_clinique' =>  'required',
            'departements' => 'required'
        ]);

        $id = $request->id_clinique;
        $departements = $request->departements;

        $clinique = Clinique::findOrFail($id) ;

       /* return response()->json([
            'state' => true,
            'data' => ['clinique' => $id, 'departements'=> $departements],
            ]);
        */


        foreach ($departements as $departement) {
            $i++;
            $tmp = array();
            $exist = Clinique_departement::where('clinique_id',$clinique->id)
                                        ->where('departement_id',$departement['id'])
                                        ->get();

            if($exist->isEmpty()){
                $cliniqueDepartement = new Clinique_departement();
                $cliniqueDepartement->clinique_id = $clinique->id;

                $cliniqueDepartement->departement_id = $departement['id'];
                $enregistrement = $cliniqueDepartement->save();

                if($enregistrement){

                    $tmp = ['state' => true,
                            'numeroEnregistrement' => $i,
                            'donneesEnregistrees' => ['idClinique' =>  $cliniqueDepartement->clinique_id, 'idDepartement' => $cliniqueDepartement->departement_id ],
                            'message' => 'Enregistrement éffectué.'
                        ];
                }
                else{
                    $tmp = [
                            'state' => false,
                            'numeroEnregistrement' => $i,
                            'donneesEnregistrees' => ['idClinique' =>  $clinique->id, 'idDepartement' => $departement['id'] ],
                            'message' => 'Echec de l\'enregistrement. Réessayer cette ligne.'
                        ];
                }

            }
            else{
                $tmp = [
                    'state' => false,
                    'numeroEnregistrement' => $i,
                    'donneesEnregistrees' => ['idClinique' =>  $exist->clinique_id, 'idDepartement' => $exist->departement_id ],
                    'message' => 'Ce enregistrement existe déja dans le base de données'
                ];
            }
            array_push($donneesEnregistrements, $tmp);

        }

        return response()->json([
            'state' => true,
            'data' => $donneesEnregistrements,
            ]);

    }

    public function servicesDepartement(Request $request, $id){

        $donneesEnregistrements = array();
        $i = 0;

        Auth::guard('api')->user();
        $departement = Departement::findOrFail($id) ;

        $request->validate([
            'services' => 'required'
        ]);

        $services = $request->services;



        foreach ($services as $service) {
            $i++;
            $tmp = array();

            $exist = Departement_service::where('departement_id',$departement->id)
                                        -> where('service_id', $service['id'])
                                        ->get();
            if($exist->isEmpty()){
                $departementService = new Departement_service();
                $departementService->departement_id = $departement->id;
                $departementService->service_id = $service['id'];
                $enregistrement = $departementService->save();

                if($enregistrement){

                    $tmp = ['state' => true,
                            'numeroEnregistrement' => $i,
                            'donneesEnregistrees' => ['idDepartement' =>  $departementService->departement_id, 'idService' => $departementService->service_id ],
                            'message' => 'Enregistrement éffectué.'
                        ];
                }
                else{
                    $tmp = [
                            'state' => false,
                            'numeroEnregistrement' => $i,
                            'donneesEnregistrees' => ['idDepartement' =>  $departementService->departement_id, 'idService' => $departementService->service_id ],
                            'message' => 'Echec de l\'enregistrement. Réessayer cette ligne.'
                        ];
                }

            }
            else{
                $tmp = [
                    'state' => false,
                    'numeroEnregistrement' => $i,
                    'donneesEnregistrees' => ['idDepartement' => $departement->id, 'idService' => $service['id'] ],
                    'message' => 'Ce enregistrement existe déja dans le base de données'
                ];
            }
            array_push($donneesEnregistrements, $tmp);


        }

        return response()->json([
            'state' => true,
            'data' => $donneesEnregistrements,
            ]);

    }

    public function lieuDeTravail(Request $request){
        Auth::guard('api')->user();

       // ['clinique_id','departement_id','service_id','user_id','statut'];
        $request->validate([
            'clinique_id' => 'required',
            'departement_id' => 'required',
            'service_id' => 'required',
            'user_id' => 'required'
        ]);

        $clinique_id = $request->clinique_id;
        $departement_id = $request->departement_id;
        $service_id = $request->service_id;
        $user_id = $request->user_id;

        $lieu_de_travail = new Lieu_de_travail();

        $lieu_de_travail->clinique_id = $clinique_id;
        $lieu_de_travail->departement_id = $departement_id;
        $lieu_de_travail->service_id = $service_id;
        $lieu_de_travail->user_id = $user_id;

        $exist = Lieu_de_travail::where('clinique_id',$lieu_de_travail->clinique_id)
                                 -> where('departement_id',$lieu_de_travail->departement_id)
                                 -> where('service_id',$lieu_de_travail->service_id)
                                 -> where('user_id',$lieu_de_travail->user_id)
                                 ->get();

        if($exist->isEmpty()){
            $enregistrement = $lieu_de_travail->save();

            if($enregistrement){
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
        else{
            return response()->json([
                'state'=> false,
                'message' => 'Cette utilisateur à déjà un lieu de travail']);
        }
    }

    public function  activerDesactiverDepartementsClinique(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'departement_id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

       // return response()->json(['message' => 'Bien.']);


        $clinique_id = $request->clinique_id;
        $departement_id = $request->departement_id;
        $statut = $request->statut;

        $data = ['statut' => $statut];

        $departementClinique = Clinique_departement::where('clinique_id', $clinique_id)
                                                    ->where('departement_id', $departement_id)
                                                    ->first();

        $statut = $departementClinique->fill($data)->save();

        //return response()->json(['message' => $departementClinique]);

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

    public function activerDesactiverServicesDepartement(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'service_id' => 'required',
            'departement_id' => 'required',
            'statut' => 'required|in:actif,inactif'
        ]);

        $service_id = $request->service_id;
        $departement_id = $request->departement_id;
        $statut = $request->statut;

        //$data = ['statut' => $statut];

        $departementService = Departement_service::where('departement_id',$departement_id)
                                                    ->where('service_id',$service_id)
                                                    ->first();


        /*$departementService = Departement_service::where('departement_id',$departement_id)
                                                ->where('service_id',$service_id)
                                                ->frist();*/



        $status = $departementService->update([
            'statut' => $statut
        ]);
        if($status){
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

    public function activerDesactiverlieuDeTravail(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'departement_id' => 'required',
            'service_id' => 'required',
            'user_id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

        $clinique_id = $request->clinique_id;
        $departement_id = $request->departement_id;
        $service_id = $request->service_id;
        $user_id = $request->user_id;
        $statut = $request->statut;

        $data = ['statut' => $statut];

        $lieu_de_travail = Lieu_de_travail::where('clinique_id',$clinique_id)
                                                -> where('departement_id',$departement_id)
                                                -> where('service_id',$service_id)
                                                -> where('user_id',$user_id)
                                                ->first();

        $statut = $lieu_de_travail->fill($data)->save();
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

    public function activerDesactiverCliniqueAnanlyse(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'analyses_id' => 'required',
            'statut' => 'required|in:actif,inactif'
        ]);

        $clinique_id = $request->clinique_id;
        $analyses_id = $request->analyses_id;
        $statut = $request->statut;

        //$data = ['statut' => $statut];

        $clinique_analyse = Clinique_analyse::where('clinique_id',$clinique_id)
                                                    ->where('analyses_id',$analyses_id)
                                                    ->first();


        /*$departementService = Departement_service::where('departement_id',$departement_id)
                                                ->where('service_id',$service_id)
                                                ->frist();*/



        $status = $clinique_analyse->update([
            'statut' => $statut
        ]);
        if($status){
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

    public function activerDesactiverCliniqueAssurance(Request $request)
    {
        Auth::guard('api')->user();

        $request->validate([
            'clinique_id' => 'required',
            'assurance_id' => 'required',
            'statut' => 'required|in:actif,inactif'
        ]);

        $clinique_id = $request->clinique_id;
        $assurance_id = $request->assurance_id;
        $statut = $request->statut;

        $clinique_analyse = Clinique_analyse::where('clinique_id',$clinique_id)
                                                    ->where('analyses_id',$assurance_id)
                                                    ->first();
        $status = $clinique_analyse->update([
            'statut' => $statut
        ]);
        if($status){
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
