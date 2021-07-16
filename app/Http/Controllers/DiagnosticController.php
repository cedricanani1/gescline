<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnostic;

class DiagnosticController extends Controller
{
    public function index()
    {
        $Diagnostic = Diagnostic::all();
        return response()->json($Diagnostic);
    }
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'string|required',
            'type' => 'string|nullable',

        ]);
            $data['libelle'] = $request['libelle'];
            $data['type']=$request['type'];

            $status = Diagnostic::create($data);

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
        $Diagnostic = Diagnostic::findOrFail($id);
        return response()->json($Diagnostic);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'string|required',
            'type' => 'string|nullable',

        ]);
        $data['libelle'] = $request['libelle'];
        $data['type']=$request['type'];

        $Diagnostic = Diagnostic::findOrFail($id);
        $status = $Diagnostic->fill($data)->save();

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
        $Diagnostic = Diagnostic::find($id);

        $Diagnostic->delete();

        return response()->json([
            'state'=> 'true',
        ]);
    }
}
