<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierExamen;
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
            'examen_id' => 'string|required',
            'purchased' => 'boolean|required',
        ]);
        $DossierExamen = DossierExamen::findOrFail($request['id']);
        if (request()->hasFile('resultat')) {
            File::delete($DossierExamen->resultat);
                $file = $request->file('resultat');
                $fileName= $request['resultat']->getClientOriginalName();
                $DossierExamen->resultat = $file->storeAs('ExamensResultats', $fileName);

        }


            $data['dossier_id'] = $request['dossier_id'];
            $data['examen_id'] = $request['examen_id'];
            $data['created_by'] = $created_by;
            $data['purchased'] = $request['purchased'];

        $status = $DossierExamen->fill($data)->save();

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
