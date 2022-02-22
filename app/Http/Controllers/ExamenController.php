<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;

class ExamenController extends Controller
{
    public function index()
    {
        $Examen = Examen::all();
        return response()->json($Examen);
    }
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'string|required',
            'prix' => 'integer|required',
        ]);
            $data['libelle'] = $request['libelle'];
            $data['prix']=$request['prix'];

            $status = Examen::create($data);

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
        $Examen = Examen::findOrFail($id);
        return response()->json($Examen);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'string|required',
            'prix' => 'integer|required',

        ]);

        $data['libelle'] = $request['libelle'];
        $data['prix']=$request['prix'];

        $Examen = Examen::findOrFail($id);
        $status = $Examen->fill($data)->save();

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
        $Examen = Examen::find($id);

        $Examen->delete();
        if ($Examen) {
            return response()->json([
                'state'=> 'true',
            ]);
        } else {
            return response()->json([
                'state'=> 'falsed',
            ]);
        }

    }
}
