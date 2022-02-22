<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierOrdonnance;
use App\Models\Medicament;
use App\Models\OrdonnanceMedicament;
use Illuminate\Support\Facades\Auth;

class DossierOrdonnanceController extends Controller
{
    public function index()
    {
        $DossierOrdonnance = DossierOrdonnance::all();
        return response()->json($DossierOrdonnance);
    }
    public function store(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'ordonnances' => 'array|required',
        ]);
            $num= mt_rand(0000000, 1000000000);
            $ordo['dossier_id'] = $request['dossier_id'];
            $ordo['num'] = $num ;
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

            // $status = OrdonnanceMedicament::create($data);
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
        $DossierOrdonnance = DossierOrdonnance::findOrFail($id);
        return response()->json($DossierOrdonnance);
    }
    public function update(Request $request, $id)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'medicament_id' => 'integer|required',
            'posologie' => 'string|required',
            'ordonnance_id' => 'string|required',
        ]);
            $data['medicament_id'] = $request['medicament_id'];
            $data['posologie'] = $request['posologie'];
            $data['created_by'] = $created_by;

            $DossierOrdonnance = DossierOrdonnance::findOrFail($id);
            $DossierOrdonnance = OrdonnanceMedicament::where('ordonnance_id',$id)
                                                     ->where('medicament_id', $request['medicament_id'])->get();
            $DossierOrdonnance->medicament_id = $data['medicament_id'];
            $DossierOrdonnance->posologie = $data['posologie'];
        $status = $DossierOrdonnance->fill($data)->save();

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

    public function caisse(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'ordonnance_id' => 'integer|required',
            'medicament_id' => 'integer|required',
        ]);
            $DossierExamen = OrdonnanceMedicament::where('ordonnance_id',$request['ordonnance_id'])->where('medicament_id',$request['medicament_id'])->first();

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

    public function delete($id){

        $DossierOrdonnance = DossierOrdonnance::find($id);
        $DossierOrdonnance->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
