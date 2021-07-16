<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Traitement;

class TraitementController extends Controller
{
    public function index()
    {
        $Traitement = Traitement::all();
        return response()->json($Traitement);
    }
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'string|required',
        ]);
            $data['libelle'] = $request['libelle'];

            $status = Traitement::create($data);

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
        $Traitement = Traitement::findOrFail($id);
        return response()->json($Traitement);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'string|required',

        ]);
        $data['libelle'] = $request['libelle'];

        $Traitement = Traitement::findOrFail($id);
        $status = $Traitement->fill($data)->save();

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
        $Traitement = Traitement::find($id);

        $Traitement->delete();

        return response()->json([
            'state'=> 'true',
        ]);
    }
}
