<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;
use App\Models\DossierAssurance;
use App\Models\DossierClient;
use App\Models\DossierConsultation;
use App\Models\Facture;
use App\Models\FileAttente;
use App\Models\workflow;
use App\Models\WorkTime;
use Carbon\Carbon;

class DossierClientController extends Controller
{
    public function store(Request $request)
    {

        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'objet' => 'string|required',
        ]);

            $num= Carbon::now()->format('YmdHi');
            $client = Client::find($request['client_id']);
            if ( empty($client) ) {
                return response()->json([
                    'state' => false,
                    'message'=> 'Ce Patient n\'existe pas',
                ]);
            }else{
                $verif= DossierClient::with('factures')->where('client_id',$client->id)->get();

                foreach ($verif as $value) {
                    foreach ($value->factures as $facture) {
                        if ($facture['sold'] === 0) {
                            if (!(isset($request['confirm'])) ) {
                                return response()->json([
                                    'state' => false,
                                    'message'=> 'Ce Patient ne peut etre consulté car il a une facture non payée',
                                ]);
                            }else{
                                $dossier['raison']= $request['raison'];
                                $dossier['debt']= 1;
                            }

                        }
                    }

                }
            }
            $worktimes = WorkTime::where('code',$request->code)->first();
            if (!$worktimes) {
                return response()->json([
                    'state'=> false,
                    'message' => 'code invalide'
                ]);
            }

            $dossier['client_id'] = $client->id;
            $dossier['num'] = 'DOS'.$num;
            $dossier['objet']= $request['objet'];
            $dossier['created_by'] = $created_by;

            $dossier = DossierClient::create($dossier);

            if ((boolean) $request['assurance']) {
                $assur['dossier_id'] = $dossier->id;
                $assur['assurance_id'] = $request['assurance_id'];
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
                $consulPrice = Consultation::findOrFail(1);
                if ($consultation) {
                    $num= Carbon::now()->format('YmdHi');
                    $data['num_facture'] = $num;
                    $data['montant_total'] = $consulPrice->prix;
                    $data['montant'] = $consulPrice->prix;
                    $data['dossier_id']=$dossier->id;
                    $data['sold']=0;
                    $status = Facture::create($data);
                }
                $service =  Auth::guard('api')->user()->service->last();
                if ($request['destination_service_id']) {
                    $fifo = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$request['destination_service_id'])->get();
                    $service =  Auth::guard('api')->user()->service->last();
                    $workflow = workflow::where('source_service_id',$service->id)->first();
                    $fileA['dossier_id'] = $dossier->id;
                    $fileA['num_ordre'] =  count($fifo)+1;
                    $fileA['service_id'] = $request['destination_service_id'];
                    $fileA['status'] = 'en attente';
                    $fileA = FileAttente::create($fileA);
                }else{
                    $fifo = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$service->id)->get();
                    $service =  Auth::guard('api')->user()->service->last();
                    $workflow = workflow::where('source_service_id',$service->id)->first();
                    $file['dossier_id'] = $dossier->id;
                    $file['num_ordre'] =count($fifo)+1;
                    $file['service_id'] = $workflow->destination_service_id;
                    $file['status'] = 'en attente';

                    $file = FileAttente::create($file);
                }

           if ($client) {
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
    public function listeExamenByDossier($id){
        $DossierExamen = DossierClient::with('examens','assurance')->findOrFail($id);
        return response()->json($DossierExamen);
    }

    public function listeDossiersByClient($id){
        $dossier = DossierClient::with('constantes','pensements','examens','traitements','assurance','diagnostics','ordonnances','rendezVous','fileAttente')->findOrFail($id);
        foreach ($dossier->ordonnances as  $value) {
            $value->medicaments;
        }
        if ($dossier) {
            return response()->json([
                'state'=> 'true',
                'data' =>$dossier
            ]);
        }else{
            return response()->json([
                'state'=> 'false',
            ]);
        }
        return response()->json($dossier);
    }
}
