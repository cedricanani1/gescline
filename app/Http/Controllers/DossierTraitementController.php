<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierTraitement;
use Illuminate\Support\Facades\Auth;

class DossierTraitementController extends Controller
{
    public function index()
    {
        $DossierTraitement = DossierTraitement::all();
        return response()->json($DossierTraitement);
    }
    public function store(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'integer|required',
            'traitement_id' => 'integer|required',
            'dose' => 'string|required',
            'voie' => 'string|required',
            'heure' => 'string|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['traitement_id'] = $request['traitement_id'];
            $data['dose'] = $request['dose'];
            $data['voie'] = $request['voie'];
            $data['heure'] = $request['heure'];
            $data['created_by'] = $created_by;
            $status = DossierTraitement::create($data);

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
        $DossierTraitement = DossierTraitement::findOrFail($id);
        return response()->json($DossierTraitement);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'dossier_id' => 'integer|required',
            'traitement_id' => 'integer|required',
            'dose' => 'string|required',
            'voie' => 'string|required',
            'heure' => 'string|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['traitement_id'] = $request['traitement_id'];
            $data['dose'] = $request['dose'];
            $data['voie'] = $request['voie'];
            $data['heure'] = $request['heure'];
            $data['created_by'] = $request['created_by'];

        $DossierTraitement = DossierTraitement::findOrFail($id);
        $status = $DossierTraitement->fill($data)->save();

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

        $DossierTraitement = DossierTraitement::find($id);
        $DossierTraitement->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
