<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierConstante;
use App\Models\DossierClient;
use Illuminate\Support\Facades\Auth;
use App\Models\FileAttente;
use App\Models\workflow;
use App\Models\WorkTime;
use Carbon\Carbon;

class DossierConstanteController extends Controller
{
    public function index()
    {
        $DossierConstante = DossierConstante::all();
        return response()->json($DossierConstante);
    }
    public function store(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'string|required',
            'constantes' => 'array|required',
        ]);
        $heure= Carbon::now()->isoFormat('HH:mm');
        foreach ($request['constantes'] as $value ) {
            if (isset($value['value'])) {
                $data['dossier_id'] = $request['dossier_id'];
                $data['constante_id'] = $value['id'];
                $data['value'] = $value['value'];
                $data['created_by'] = $created_by;
                $data['heure'] = $heure;
                $status = DossierConstante::create($data);
            }

        }
            if (!$request->isMedecin) {
                if ($request['destination_service_id']) {

                    $service =  Auth::guard('api')->user()->service->last();
                    $workflow = workflow::where('source_service_id',$service->id)->first();
                    $file = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$service->id)->get();
                    $fifo = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$service->id)->first();

                    $fifo->dossier_id = $request['dossier_id'];
                    $fifo->num_ordre =count($file)+1;
                    $fifo->service_id = $request['destination_service_id'];
                    $fifo->status = 'en attente';
                    $fifo->save();
                }
            }


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
    public function constantesByDossier($id)
    {
        // $DossierTraitement = DossierClient::with('constantes')->findOrFail($id);
        $DossierTraitement = DossierConstante::where('dossier_id',$id)->get()->groupBy('heure');
        return response()->json($DossierTraitement);
    }

    public function show($id)
    {
        $DossierConstante = DossierConstante::findOrFail($id);
        return response()->json($DossierConstante);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'dossier_id' => 'string|required',
            'constante_id' => 'string|required',
            'value' => 'string|required',
            'created_by' => 'integer|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['constante_id'] = $request['constante_id'];
            $data['value'] = $request['value'];
            $data['created_by'] = $request['created_by'];

        $DossierConstante = DossierConstante::findOrFail($id);
        $status = $DossierConstante->fill($data)->save();

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

        $DossierConstante = DossierConstante::find($id);
        $DossierConstante->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
