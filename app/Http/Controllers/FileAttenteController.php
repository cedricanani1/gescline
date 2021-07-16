<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileAttente;

class FileAttenteController extends Controller
{
    public function index()
    {
        $FileAttente = FileAttente::all();
        return response()->json($FileAttente);
    }
    public function store(Request $request)
    {
            // $request->validate([
            //     'libelle' => 'string|required',
            //     'prix' => 'string|required',

            // ]);
            $fifo = FileAttente::all();
            $file['dossier_id'] = $request['dossier_id'];
            $file['num_ordre'] = count($fifo)+1;
            $file['profile_id'] = $request['profile_id'];
            $file['status'] = $request['status'];

            $status = FileAttente::create($file);

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
    public function getFileByProjet(Request $request)
    {
            // $request->validate([
            //     'libelle' => 'string|required',
            //     'prix' => 'string|required',

            // ]);
            $fifo = FileAttente::where('profile_id',$request->profile_id);

            if ($fifo) {
                return response()->json([
                    'state'=> 'true',
                    'data'=> $fifo,
                ]);
            }else{
                return response()->json([
                    'state'=> 'false',
                ]);
            }

    }
    public function show($id)
    {
        $FileAttente = FileAttente::findOrFail($id);
        return response()->json($FileAttente);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'dossier_id' => 'integer|required',
            'profile_id' => 'integer|required',
            'status' => 'string|required',

        ]);

        $file['dossier_id'] = $request['dossier_id'];
        $file['profile_id'] = $request['profile_id'];
        $file['status'] = $request['status'];

        $FileAttente = FileAttente::findOrFail($id);
        $status = $FileAttente->fill($file)->save();

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
        $FileAttente = FileAttente::find($id);

        $FileAttente->delete();

        return response()->json([
            'state'=> 'true',
        ]);
    }
}
