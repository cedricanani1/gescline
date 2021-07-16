<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierRendezVous;
use Illuminate\Support\Facades\Auth;

class DossierRendezVousController extends Controller
{
    public function index()
    {
        $DossierRendezVous = DossierRendezVous::all();
        return response()->json($DossierRendezVous);
    }
    public function store(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'string|required',
            'bilan' => 'string|required',
            'date_rdv' => 'string|required',
            'heure_rdv' => 'string|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['bilan'] = $request['bilan'];
            $data['date_rdv'] = $request['date_rdv'];
            $data['heure_rdv'] = $request['heure_rdv'];
            $data['created_by'] = $created_by;
            $status = DossierRendezVous::create($data);

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
        $DossierRendezVous = DossierRendezVous::findOrFail($id);
        return response()->json($DossierRendezVous);
    }
    public function update(Request $request, $id)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'string|required',
            'bilan' => 'string|required',
            'date_rdv' => 'string|required',
            'heure_rdv' => 'string|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['bilan'] = $request['bilan'];
            $data['date_rdv'] = $request['date_rdv'];
            $data['heure_rdv'] = $request['heure_rdv'];
            $data['created_by'] = $created_by;

        $DossierRendezVous = DossierRendezVous::findOrFail($id);
        $status = $DossierRendezVous->fill($data)->save();

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

        $DossierRendezVous = DossierRendezVous::find($id);
        $DossierRendezVous->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
