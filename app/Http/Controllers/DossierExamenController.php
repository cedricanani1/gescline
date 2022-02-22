<?php

namespace App\Http\Controllers;

use App\Models\DossierAssurance;
use App\Models\DossierClient;
use Illuminate\Http\Request;
use App\Models\DossierExamen;
use App\Models\FileAttente;
use App\Models\workflow;
use Illuminate\Support\Facades\Auth;
use File;

class DossierExamenController extends Controller
{
    public function index()
    {
        $DossierExamen = DossierExamen::all();
        return response()->json($DossierExamen);
    }
    public function store(Request $request)
    {
            $created_by = Auth::guard('api')->user()->id;
            $request->validate([
                'dossier_id' => 'string|required',
                'examen_id' => 'string|required',
                'purchased' => 'boolean|required',
            ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['examen_id'] = $request['examen_id'];
            $data['purchased'] = $request['purchased'];
            $data['assurance'] = $request['assurance'];
            $data['created_by'] = $created_by;

            # code...
            if (request()->hasFile('resultat')) {
                $file = $request->file('resultat');
                $fileName= $request['resultat']->getClientOriginalName();
                $data['resultat'] = $file->storeAs('ExamensResultats', $fileName);
            }

            $status = DossierExamen::create($data);

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
        $DossierExamen = DossierExamen::findOrFail($id);
        return response()->json($DossierExamen);
    }
    public function update(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'string|required',
        ]);

            $DossierExamen = DossierExamen::findOrFail($request['id']);

            if (request()->hasFile('resultat')) {
                if ($DossierExamen->purchased) {
                    File::delete($DossierExamen->resultat);
                    $file = $request->file('resultat');
                    $fileName= $request['resultat']->getClientOriginalName();
                    $DossierExamen->resultat = $file->storeAs('ExamensResultats', $fileName);
                }else{
                    if (!(isset($request['confirm']))) {
                        return response()->json([
                            'state'=> false,
                            'message'=> 'Vous n\'avez pas encore fait le paiement pour cet examen',
                            'data' => $request->all()
                        ]);


                    }else {
                        File::delete($DossierExamen->resultat);
                        $file = $request->file('resultat');
                        $fileName= $request['resultat']->getClientOriginalName();
                        $DossierExamen->resultat = $file->storeAs('ExamensResultats', $fileName);
                    }
                }

            }

            $DossierExamen->created_by = $created_by;
            $assurance = DossierAssurance::where('dossier_id',$DossierExamen->dossier_id)->first();
            if ($assurance) {
                $DossierExamen->assurance = $request['assurance'];
            }
            $DossierExamen->resultatText = $request['resultatText'];
            $status = $DossierExamen->save();


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

    public function caisse(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'integer|required',
        ]);
            $DossierExamen = DossierExamen::where('dossier_id',$request['dossier_id'])->where('examen_id',$request['examen_id'])->first();

            $DossierExamen->purchased = $request['purchased'];
            $status = $DossierExamen->save();

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
    public function transferer(Request $request){

            $request->validate([
                'dossier_id' => 'integer|required',
                'destination_service_id' => 'integer|required',
            ]);
            $service =  Auth::guard('api')->user()->service->last();
            $fifo = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$request['destination_service_id'])->get();
            // $workflow = workflow::where('source_service_id',$service->id)->where('destination_service_id',$request['destination_service_id'])->first();
            $fileActuel = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$request['destination_service_id'])->first();
            if ($fileActuel) {
                $fileActuel->status = 'en attente';
                $status = $fileActuel->save();

                $maj = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$service->id)->first();
                $maj->status = 'termine';
                $maj->save();
            }else{
                $file['dossier_id'] = $request['dossier_id'];
                $file['num_ordre'] =  count($fifo)+1;
                $file['service_id'] = $request['destination_service_id'];
                $file['status']= 'en attente';
                $status = FileAttente::create($file);

                $maj = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$service->id)->first();
                $maj->status = 'termine';
                $maj->save();
            }


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

        $DossierExamen = DossierExamen::find($id);
        $DossierExamen->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
