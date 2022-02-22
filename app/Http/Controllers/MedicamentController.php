<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicament;

class MedicamentController extends Controller
{
    public function index()
    {
        $Medicament = Medicament::with('categorie')->get();
        return response()->json($Medicament);
    }
    public function store(Request $request)
    {
        $request->validate([
            'categorie_medicament_id' => 'integer|required',
            'libelle' => 'string|required',
            'dosage' => 'string|required',
            'type' => 'string|required',
            'prix' => 'integer|required',

        ]);
            $data['categorie_medicament_id']=$request['categorie_medicament_id'];
            $data['libelle'] = $request['libelle'];
            $data['dosage']=$request['dosage'];
            $data['type'] = $request['type'];
            $data['prix']=$request['prix'];
            $data['quantity']=$request['quantity'];

            $status = Medicament::create($data);

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
        $Medicament = Medicament::findOrFail($id);
        return response()->json($Medicament);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'categorie_medicament_id' => 'integer|required',
            'libelle' => 'string|required',
            'dosage' => 'string|required',
            'type' => 'string|required',
            'prix' => 'integer|required',

        ]);

        $data['categorie_medicament_id']=$request['categorie_medicament_id'];
        $data['libelle'] = $request['libelle'];
        $data['dosage']=$request['dosage'];
        $data['type'] = $request['type'];
        $data['prix']=$request['prix'];
        $data['quantity']=$request['quantity'];

        $Medicament = Medicament::findOrFail($id);
        $status = $Medicament->fill($data)->save();

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
        $Medicament = Medicament::find($id);

        $Medicament->delete();

        return response()->json([
            'state'=> true,
        ]);
    }
}
