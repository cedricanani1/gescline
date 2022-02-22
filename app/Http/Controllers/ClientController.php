<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\DossierAssurance;
use App\Models\DossierClient;
use App\Models\DossierConsultation;
use App\Models\Facture;
use App\Models\FileAttente;
use App\Models\workflow;
use App\Models\WorkTime;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('dossiers')->orderBy('created_at','DESC')->get();
        foreach ($clients as $key => $client) {
            foreach ($client->dossiers as $key => $value) {
                $value->assurance;
            }

        }
        return response()->json($clients);
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
            'lieu_naissance' => 'string|nullable',
            'residence_ville' => 'string|nullable',
            'quartier' => 'string|nullable',
            'contacts_fixe' => 'string|nullable',
            'contacts_cel' => 'string|required|unique:clients',
            'email' => 'string|unique:clients',
            'assurance' => 'boolean|required',
            'nom_assurance' => 'string|nullable',
            'profession' => 'string|nullable',
            'formation' => 'string|nullable',
            'etat_professionnel' => 'string|nullable',
            'instruction' => 'string|nullable',
            'status_matrimonial' => 'string|nullable',
        ]);
             $worktimes = WorkTime::where('code',$request->code)->first();
            if (!$worktimes) {
                return response()->json([
                    'state'=> false,
                    'message' => 'code invalide'
                ]);
            }
            $num= Carbon::now()->isoFormat('YMMDDHmmSSS');
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
            if (request()->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName= $request['photo']->getClientOriginalName();
                $data['photo'] = $file->storeAs('Clients', $fileName);
            }

            $client = Client::create($data);

            $dossier['client_id'] = $client->id;
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
                $assur['pourcentage'] = $request['pourcentage'];
                $assur['created_by'] = $created_by;
                $status = DossierAssurance::create($assur);
            }

                $consul['consultation_id'] = 1;
                $consul['dossier_id'] = $dossier->id;
                $consul['created_by'] = $created_by;

                $consultation = DossierConsultation::create($consul);

                if ($consultation) {
                    $num= Carbon::now()->format('YmdHi');
                    $data['num_facture'] = $num;
                    $data['dossier_id']=$dossier->id;
                    $data['sold']=0;
                    $status = Facture::create($data);
                }


                    if ($request['destination_service_id']) {


                        $fifo = FileAttente::all();
                        $service =  Auth::guard('api')->user()->service->last();
                        $workflow = workflow::where('source_service_id',$service->id)->first();
                        $fileA['dossier_id'] = $dossier->id;
                        $fileA['num_ordre'] =  count($fifo)+1;
                        $fileA['service_id'] = $request['destination_service_id'];
                        $fileA['status'] = 'en attente';
                        $fileA = FileAttente::create($fileA);
                    }




           if ($status) {
                return response()->json([
                    'state'=> true,
                    'data'=>$dossier
                ]);
            }else{
                return response()->json([
                    'state'=> false,

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
                'state'=> true,
            ]);
        }else{
            return response()->json([
                'state'=> false,
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
