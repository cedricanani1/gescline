<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierOrdonnance;
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
            'medicaments' => 'array|required',
        ]);
            $num= mt_rand(0000000, 1000000000);

            $ordo['num'] = $num ;
            $ordo['created_by'] = $created_by;
            $ordo = DossierOrdonnance::create($ordo);
            foreach ($request['medicaments'] as  $value) {
                $data['medicament_id'] = $value['medicament_id'];
                $data['ordonnance_id'] = $ordo->id;
                $data['quantity'] = $value['quantity'];
                $data['posologie'] = $value['posologie'];
            }

            $status = OrdonnanceMedicament::create($data);
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

    public function delete($id){

        $DossierOrdonnance = DossierOrdonnance::find($id);
        $DossierOrdonnance->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
