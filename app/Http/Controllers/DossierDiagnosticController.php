<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierDiagnostic;
use Illuminate\Support\Facades\Auth;

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
            'diagnostic_id' => 'integer|required',
            'value' => 'string|required',
            'description' => 'string',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['diagnostic_id'] = $request['diagnostic_id'];
            $data['value'] = $request['value'];
            $data['description'] = $request['description'];
            $data['created_by'] = $created_by;
            $status = DossierDiagnostic::create($data);

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
        $DossierDiagnostic = DossierDiagnostic::findOrFail($id);
        return response()->json($DossierDiagnostic);
    }
    public function update(Request $request, $id)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'integer|required',
            'diagnostic_id' => 'integer|required',
            'value' => 'string|required',
            'description' => 'string',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['diagnostic_id'] = $request['diagnostic_id'];
            $data['value'] = $request['value'];
            $data['description'] = $request['description'];
            $data['created_by'] = $created_by;

        $DossierDiagnostic = DossierDiagnostic::findOrFail($id);
        $status = $DossierDiagnostic->fill($data)->save();

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

        $DossierDiagnostic = DossierDiagnostic::find($id);
        $DossierDiagnostic->delete();
        return response()->json([
            'state'=> 'true',
        ]);

    }
}
