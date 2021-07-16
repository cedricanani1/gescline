<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\DossierAssurance;
use App\Models\DossierClient;
use App\Models\FileAttente;

class ClientController extends Controller
{
    public function index()
    {
        $client = Client::all();
        return response()->json($client);
    }
    public function store(Request $request)
    {

        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'nom' => 'string|required',
            'prenoms' => 'string|required',
            'sexe' => 'string|required',
            'date_naissance' => 'string|required',
            'nationalite' => 'string|required',
            'ethnie' => 'string|nullable',
            'lieu_naissance' => 'string|required',
            'residence_ville' => 'string|required',
            'quartier' => 'string|required',
            'contacts_fixe' => 'string|nullable',
            'contacts_cel' => 'string|required',
            'assurance' => 'boolean|required',
            'nom_assurance' => 'string|nullable',
            'profession' => 'string|nullable',
            'formation' => 'string|nullable',
            'etat_professionnel' => 'string|required',
            'instruction' => 'string|required',
            'status_matrimonial' => 'string|required',

        ]);
            $num= mt_rand(0000000, 1000000000);
            $data['matricule']='PAT'.$num;
            $data['nom']=$request['nom'];
            $data['prenoms'] = $request['prenoms'];
            $data['sexe'] = $request['sexe'];
            $data['date_naissance']= $request['date_naissance'];
            $data['nationalite']= $request['nationalite'];
            $data['ethnie'] = $request['ethnie'];
            $data['email'] = $request['email'];
            $data['lieu_naissance'] = $request['lieu_naissance'];
            $data['residence_ville'] = $request['residence_ville'];
            $data['quartier'] = $request['quartier'];
            $data['contacts_fixe'] = $request['contacts_fixe'];
            $data['contacts_cel'] = $request['contacts_cel'];
            $data['assurance']=$request['assurance'];
            $data['nom_assurance'] = $request['nom_assurance'];
            $data['profession'] = $request['profession'];
            $data['formation']= $request['formation'];
            $data['etat_professionnel']= $request['etat_professionnel'];
            $data['instruction'] = $request['instruction'];
            $data['status_matrimonial'] = $request['status_matrimonial'];

            $status = Client::create($data);

            $dossier['client_id'] = $status->id;
            $dossier['num'] = 'DOS'.$num;
            $dossier['objet']= $request['objet'];
            $dossier['created_by'] = $created_by;

            $dossier = DossierClient::create($dossier);

            if ((boolean) $request['assurance']) {
                $assur['dossier_id'] = $dossier->id;
                $assur['assurance_id'] = $request['assurance_id'];
                $assur['numero_bon'] = $request['numero_bon'];
                $assur['matricule'] = $request['matricule'];
                $assur['acte'] = $request['acte'];
                $assur['created_by'] = $created_by;
                $status = DossierAssurance::create($assur);
            }
                $fifo = FileAttente::all();

                $file['dossier_id'] = $dossier->id;
                $file['num_ordre'] ='00'.count($fifo)+1;
                $file['profile_id'] = Auth::guard('api')->user()->profile->last()->id;
                $file['status'] = 'en attente';

            $file = FileAttente::create($file);

           if ($status) {
                return response()->json([
                    'state'=> 'true',
                ]);
            }else{
                return response()->json([
                    'state'=> 'false',
                ]);
            }
    }
    public function show($id)
    {
        $client = Client::with('dossiers')->findOrFail($id);
        foreach ($client->dossiers as $dossier) {
            $dossier->assurance;
        }
        return response()->json($client);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'string|required',
            'prenoms' => 'string|required',
            'sexe' => 'string|required',
            'date_naissance' => 'string|required',
            'nationalite' => 'string|required',
            'ethnie' => 'string|nullable',
            'lieu_naissance' => 'string|required',
            'residence_ville' => 'string|required',
            'quartier' => 'string|required',
            'contacts_fixe' => 'string|nullable',
            'contacts_cel' => 'string|required',
            'assurance' => 'boolean|required',
            'nom_assurance' => 'string|nullable',
            'profession' => 'string|nullable',
            'formation' => 'string|nullable',
            'etat_professionnel' => 'string|required',
            'instruction' => 'string|required',
            'status_matrimonial' => 'string|required',

        ]);
        $data['nom']=$request['nom'];
        $data['prenoms'] = $request['prenoms'];
        $data['sexe'] = $request['sexe'];
        $data['date_naissance']= $request['date_naissance'];
        $data['nationalite']= $request['nationalite'];
        $data['ethnie'] = $request['ethnie'];
        $data['email'] = $request['email'];
        $data['lieu_naissance'] = $request['lieu_naissance'];
        $data['residence_ville'] = $request['residence_ville'];
        $data['quartier'] = $request['quartier'];
        $data['contacts_fixe'] = $request['contacts_fixe'];
        $data['contacts_cel'] =$request['contacts_cel'];
        $data['assurance']=$request['assurance'];
        $data['nom_assurance'] = $request['nom_assurance'];
        $data['profession'] = $request['profession'];
        $data['formation']= $request['formation'];
        $data['etat_professionnel']= $request['etat_professionnel'];
        $data['instruction'] = $request['instruction'];
        $data['status_matrimonial'] = $request['status_matrimonial'];
        $client = Client::findOrFail($id);
        $status = $client->fill($data)->save();

        $client->profile()->updateExistingPivot($client->id, array('profile_id' => $request['profile_id']), true);

        if ($status) {
            return response()->json([
                'state'=> 'true',
            ]);
        }else{
            return response()->json([
                'state'=> 'false',
            ]);
        }
    }

    public function delete($id){
        $client = Client::find($id);

        $client->delete();

        return response()->json([
            'state'=> 'true',
        ]);
    }
}
