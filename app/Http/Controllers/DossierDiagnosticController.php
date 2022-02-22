<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierDiagnostic;
use App\Models\DossierExamen;
use App\Models\DossierOrdonnance;
use App\Models\DossierPensement;
use Illuminate\Support\Facades\Auth;
use App\Models\workflow;
use App\Models\FileAttente;
use App\Models\Medicament;
use App\Models\OrdonnanceMedicament;
use App\Models\WorkTime;
use Carbon\Carbon;

class DossierDiagnosticController extends Controller
{
    public function index()
    {
        $DossierDiagnostic = DossierDiagnostic::all();
        return response()->json($DossierDiagnostic);
    }
    public function store(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'integer|required',
        ]);


            $service =  Auth::guard('api')->user()->service->last();

            if (count($request['diagnostics']) > 0) {

                foreach ($request['diagnostics'] as $value) {
                    if (isset($value['value'])) {
                        $verif = DossierDiagnostic::where('dossier_id',$request['dossier_id'])->where('diagnostic_id',$value['id'])->first();
                        if ($verif) {
                            $data['dossier_id'] = $request['dossier_id'];
                            $data['diagnostic_id'] = $value['id'];
                            $data['value'] = $value['value'];
                            if (isset($value['description'])) {
                                $data['description'] = $value['description'];
                            }
                            $data['created_by'] = $created_by;
                            $status = $verif->fill($data)->save();
                        }else{
                            $data['dossier_id'] = $request['dossier_id'];
                            $data['diagnostic_id'] = $value['id'];
                            $data['value'] = $value['value'];
                            if (isset($value['description'])) {
                                $data['description'] = $value['description'];
                            }
                            $data['created_by'] = $created_by;
                            $status = DossierDiagnostic::create($data);
                        }

                    }

                }

            }

            if (count($request['examens']) > 0) {

                foreach ($request['examens'] as  $value) {
                    if (isset($value['value'])) {
                        $verif = DossierExamen::where('dossier_id',$request['dossier_id'])->where('examen_id',$value['id'])->first();
                        if ($verif) {
                            $data['dossier_id'] = $request['dossier_id'];
                            $data['examen_id'] = $value['id'];
                            $data['created_by'] = $created_by;
                            $status = $verif->fill($data)->save();
                        }else{
                            $data['dossier_id'] = $request['dossier_id'];
                            $data['examen_id'] = $value['id'];
                            $data['created_by'] = $created_by;
                            $status = DossierExamen::create($data);
                        }
                    }
                }
            }

            if (count($request['pensements']) > 0) {

                foreach ($request['pensements'] as  $value) {
                    if (isset($value['value'])) {
                        $verif = DossierPensement::where('dossier_id',$request['dossier_id'])->where('medicament_id',$value['id'])->first();
                        if ($verif) {
                            $data['dossier_id'] = $request['dossier_id'];
                            $data['medicament_id'] = $value['id'];
                            $data['created_by'] = $created_by;
                            $status = $verif->fill($data)->save();
                        }else {
                            $data['dossier_id'] = $request['dossier_id'];
                            $data['medicament_id'] = $value['id'];
                            $data['created_by'] = $created_by;
                            $status = DossierPensement::create($data);
                        }

                    }

                }

            }

            if (count($request['ordonnances']) > 0) {
                $num= mt_rand(0000000, 1000000000);
                $ordo['num'] = $num ;
                $ordo['dossier_id']= $request['dossier_id'];
                $ordo['created_by'] = $created_by;
                $ordo = DossierOrdonnance::create($ordo);

                foreach ($request['ordonnances'] as  $value) {
                    if (strlen($value['medicament_id']) > 0) {
                        $medicament = Medicament::findOrFail($value['medicament_id']);
                        if ($medicament) {
                            $data['medicament_id'] = $value['medicament_id'];
                            $data['ordonnance_id'] = $ordo->id;
                            $data['quantity'] = $value['quantite'];
                            $data['posologie'] = $value['posologie'];
                            $status = OrdonnanceMedicament::create($data);
                        }else{
                            return response()->json([
                                'state'=> 'false',
                                'message'=> 'Ce Medimament n`\existe pas',
                            ]);
                        }

                    }else{
                            $data['medicament_name'] = $value['medicament'];
                            $data['ordonnance_id'] = $ordo->id;
                            $data['quantity'] = $value['quantite'];
                            $data['posologie'] = $value['posologie'];
                            $status = OrdonnanceMedicament::create($data);
                    }

                }
            }



            if (count($request['workflows']) > 0) {


                foreach ($request['workflows'] as  $value) {


                    if (isset($value['value'])) {

                        $fifo = FileAttente::where('service_id',$value['destination_service_id'])->get();
                        $file = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$value['destination_service_id'])->first();
                        if ($file) {
                            $file->status= 'en attente';
                            $file->save();

                        }else {
                            $file['dossier_id'] = $request['dossier_id'];
                            $file['num_ordre'] =  count($fifo)+1;
                            $file['service_id'] = $value['destination_service_id'];
                            $file['status']= 'en attente';
                            $file = FileAttente::create($file);
                        }

                        $fileActuel = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$value['source_service_id'])->first();
                        if ($fileActuel) {
                            $fileActuel->status = 'termine';
                            $fileActuel->save();
                        }else{
                            return response()->json([
                                'state'=> false,
                                'message'=> 'Vous ne pouvez pas Transferez ce dossier car est en cours de traitement dans un autre service',
                            ]);
                        }

                        $status = true;
                    }else{



                            // $file['status'] = 'termine';
                            // $FileAttente = FileAttente::where('dossier_id',$request['dossier_id'])->get();
                            // if ($FileAttente) {
                            //     foreach ($FileAttente as $key => $value) {
                            //         $status = $value->fill($file)->save();
                            //     }
                            // }

                            // $status = true;
                    }

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
    public function show($id)
    {
        $DossierDiagnostic = DossierDiagnostic::findOrFail($id);
        return response()->json($DossierDiagnostic);
    }
    public function update(Request $request, $id)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'integer|required',
        ]);


            $service =  Auth::guard('api')->user()->service->last();

            if (count($request['diagnostics']) > 0) {

                foreach ($request['diagnostics'] as $value) {
                    if (isset($value['value'])) {
                        $DossierDiagnostic = DossierDiagnostic::findOrFail($value['id']);
                        $data['dossier_id'] = $request['dossier_id'];
                        $data['diagnostic_id'] = $value['diagnostic_id'];
                        $data['value'] = $value['value'];
                        if (isset($value['description'])) {
                            $data['description'] = $value['description'];
                        }
                        $data['created_by'] = $created_by;
                        $status = $DossierDiagnostic->fill($data)->save();
                    }

                }

            }

            if (count($request['examens']) > 0) {

                foreach ($request['examens'] as  $value) {
                    if (isset($value['value'])) {
                        $DossierExamen = DossierExamen::findOrFail($value['id']);
                        $data['dossier_id'] = $request['dossier_id'];
                        $data['examen_id'] = $value['examen_id'];
                        $data['created_by'] = $created_by;
                        $status = $DossierExamen->fill($data)->save();
                    }

                }
            }

            if (count($request['pensements']) > 0) {

                foreach ($request['pensements'] as  $value) {
                    if (isset($value['value'])) {
                        $DossierPensement = DossierPensement::findOrFail($value['id']);
                        $data['dossier_id'] = $request['dossier_id'];
                        $data['medicament_id'] = $value['medicament_id'];
                        $data['created_by'] = $created_by;
                        $status = $DossierPensement->fill($data)->save();
                    }

                }

            }

            if (count($request['ordonnances']) > 0) {
                $OrdonnanceMedicament = DossierOrdonnance::findOrFail($value['id']);
                $ordo['created_by'] = $created_by;
                $ordo = $OrdonnanceMedicament->fill($data)->save();

                foreach ($request['ordonnances'] as  $value) {
                    if (strlen($value['medicament_id']) > 0) {
                        $medicament = Medicament::findOrFail($value['medicament_id']);
                        if ($medicament) {
                            $OrdonnanceMedicament = OrdonnanceMedicament::findOrFail($value['id']);
                            $data['medicament_id'] = $value['medicament_id'];
                            $data['ordonnance_id'] = $ordo->id;
                            $data['quantity'] = $value['quantite'];
                            $data['posologie'] = $value['posologie'];
                            $status = $OrdonnanceMedicament->fill($data)->save();
                        }else{
                            return response()->json([
                                'state'=> 'false',
                                'message'=> 'Ce Medimament n`\existe pas',
                            ]);
                        }

                    }else{
                            $OrdonnanceMedicament = OrdonnanceMedicament::findOrFail($value['id']);

                            $data['medicament_name'] = $value['medicament'];
                            $data['ordonnance_id'] = $ordo->id;
                            $data['quantity'] = $value['quantite'];
                            $data['posologie'] = $value['posologie'];
                            $status = $OrdonnanceMedicament->fill($data)->save();
                    }

                }
            }



            if (count($request['workflows']) > 0) {


                foreach ($request['workflows'] as  $value) {


                    if (isset($value['value'])) {

                        $fifo = FileAttente::where('service_id',$value['destination_service_id'])->get();
                        $file = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$value['destination_service_id'])->first();
                        if ($file) {
                            $file->status= 'en attente';
                            $file->save();

                        }else {

                            $file['dossier_id'] = $request['dossier_id'];
                            $file['num_ordre'] =  count($fifo)+1;
                            $file['service_id'] = $value['destination_service_id'];
                            $file['status']= 'en attente';
                            $file = FileAttente::create($file);

                        }

                        $fileActuel = FileAttente::where('dossier_id',$request['dossier_id'])->where('service_id',$value['source_service_id'])->first();
                        $fileActuel->status = 'termine';
                        $fileActuel->save();

                        $status = true;
                    }

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

    public function delete($id){

        $DossierDiagnostic = DossierDiagnostic::find($id);
        $DossierDiagnostic->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
