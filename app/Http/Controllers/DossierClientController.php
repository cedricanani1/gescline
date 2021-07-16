<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\DossierAssurance;
use App\Models\DossierClient;
use App\Models\FileAttente;

class DossierClientController extends Controller
{
    public function store(Request $request)
    {

        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'objet' => 'string|required',
        ]);
            $num= mt_rand(0000000, 1000000000);
            $client = Client::findOrFail($request['client_id']);
            if ( empty($client) ) {
                return response()->json([
                    'message'=> 'Ce Patient n\'existe pas',
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

           if ($client) {
                return response()->json([
                    'state'=> 'true',
                ]);
            }else{
                return response()->json([
                    'state'=> 'false',
                ]);
            }
    }
}
