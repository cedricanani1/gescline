<?php

namespace App\Http\Controllers;

use App\Models\DossierClient;
use Illuminate\Http\Request;
use App\Models\DossierPensement;
use Illuminate\Support\Facades\Auth;
class DossierPensementController extends Controller
{
    public function index()
    {
        $DossierPensement = DossierPensement::all();
        return response()->json($DossierPensement);
    }
    public function store(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'string|required',
            'medicament_id' => 'string|required',
            'value' => 'string|required',
            'purchased' => 'boolean|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['medicament_id'] = $request['medicament_id'];
            $data['purchased'] = $request['purchased'];
            $data['created_by'] = $created_by;
            $status = DossierPensement::create($data);

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
        $DossierPensement = DossierPensement::findOrFail($id);
        return response()->json($DossierPensement);
    }

    public function pensementByDossier($id)
    {
        $DossierPensement = DossierClient::with('pensements')->findOrFail($id);
        return response()->json($DossierPensement);
    }
    public function update(Request $request, $id)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'string|required',
            'medicament_id' => 'string|required',
            'purchased' => 'boolean|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['medicament_id'] = $request['medicament_id'];
            $data['purchased'] = $request['purchased'];
            $data['created_by'] = $created_by;

        $DossierPensement = DossierPensement::findOrFail($id);
        $status = $DossierPensement->fill($data)->save();

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
            'dossier_id' => 'integer|required',
        ]);
            $DossierExamen = DossierPensement::where('dossier_id',$request['dossier_id'])->where('medicament_id',$request['medicament_id'])->first();

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

        $DossierPensement = DossierPensement::find($id);
        $DossierPensement->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
