<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;
use App\Models\FileAttente;
use App\Models\workflow;
use Illuminate\Support\Facades\Auth;

class FileAttenteController extends Controller
{
    public function index()
    {
        // $FileAttente = FileAttente::with('dossier')->get()->unique('dossier.client_id');
        $FileAttente = Facture::with('dossier')->where('sold',0)->get();
        foreach ($FileAttente as  $value) {
            $value->dossier['client'];
        }
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
            $file['service_id'] = $request['service_id'];
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
    public function getFileByProjet()
    {
            // $request->validate([
            //     'libelle' => 'string|required',
            //     'prix' => 'string|required',

            // ]);
            $service= Auth::guard('api')->user()->service->last();
            $fifo = FileAttente::with('dossier')->where('service_id',$service->id)->where('status','<>','termine')->get();

            foreach ($fifo as  $value) {
                $value->dossier->client;
                $value->dossier->examens;
            }

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
        // $request->validate([
        //     'status' => 'string|required',
        // ]);

        $file['status'] = $request['status'];
        $FileAttente = FileAttente::where('dossier_id',$id)->get();

        if ($FileAttente) {
            foreach ($FileAttente as $key => $value) {
                $status = $value->fill($file)->save();
            }
        }else{

        }

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
        $FileAttente = FileAttente::find($id);

        $FileAttente->delete();

        return response()->json([
            'state'=> 'true',
        ]);
    }
}
