<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierConstante;
use Illuminate\Support\Facades\Auth;

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
            'constante_id' => 'string|required',
            'value' => 'string|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['constante_id'] = $request['constante_id'];
            $data['value'] = $request['value'];
            $data['created_by'] = $created_by;
            $status = DossierConstante::create($data);

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
                'state'=> 'true',
            ]);
        }else{
            return response()->json([
                'state'=> 'false',
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
