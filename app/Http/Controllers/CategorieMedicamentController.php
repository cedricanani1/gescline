<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategorieMedicament;

class CategorieMedicamentController extends Controller
{
    public function index()
    {
        $CategorieMedicament = CategorieMedicament::all();
        return response()->json($CategorieMedicament);
    }
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'string|required',
            'specification' => 'string|required',

        ]);
            $data['libelle']=$request['libelle'];
            $data['specification'] = $request['specification'];

            $status = CategorieMedicament::create($data);

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
    public function show($id)
    {
        $CategorieMedicament = CategorieMedicament::findOrFail($id);
        return response()->json($CategorieMedicament);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'string|required',
            'specification' => 'string|required',

        ]);
        $data['libelle']=$request['libelle'];
        $data['specification'] = $request['specification'];

        $CategorieMedicament = CategorieMedicament::findOrFail($id);
        $status = $CategorieMedicament->fill($data)->save();

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
        $CategorieMedicament = CategorieMedicament::find($id);

        $CategorieMedicament->delete();

        return response()->json([
            'state'=> true,
        ]);
    }
}
