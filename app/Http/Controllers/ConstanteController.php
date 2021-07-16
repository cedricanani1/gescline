<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Constante;

class ConstanteController extends Controller
{
    public function index()
    {
        $Constante = Constante::all();
        return response()->json($Constante);
    }
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'string|required',
        ]);
            $data['libelle'] = $request['libelle'];

            $status = Constante::create($data);

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
        $Constante = Constante::findOrFail($id);
        return response()->json($Constante);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'string|required',

        ]);
        $data['libelle'] = $request['libelle'];

        $Constante = Constante::findOrFail($id);
        $status = $Constante->fill($data)->save();

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
        $Constante = Constante::find($id);

        $Constante->delete();

        return response()->json([
            'state'=> 'true',
        ]);
    }
}
