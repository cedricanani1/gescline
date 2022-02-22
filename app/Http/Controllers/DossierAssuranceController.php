<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierAssurance;
use App\Models\WorkTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DossierAssuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $DossierAssurance = DossierAssurance::all();
        return response()->json($DossierAssurance);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'string|required',
            'assurance_id' => 'string|required',
            'numero_bon' => 'string|required',
            'matricule' => 'string|required',
            'acte' => 'string|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['assurance_id'] = $request['assurance_id'];
            $data['numero_bon'] = $request['numero_bon'];
            $data['matricule'] = $request['matricule'];
            $data['acte'] = $request['acte'];
            $data['created_by'] = $created_by;


            $status = DossierAssurance::create($data);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $DossierExamen = DossierAssurance::findOrFail($id);
        return response()->json($DossierExamen);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $created_by = Auth::guard('api')->user()->id;
        $request->validate([
            'dossier_id' => 'string|required',
            'assurance_id' => 'string|required',
            'numero_bon' => 'string|required',
            'matricule' => 'string|required',
            'acte' => 'string|required',
        ]);
            $data['dossier_id'] = $request['dossier_id'];
            $data['assurance_id'] = $request['assurance_id'];
            $data['numero_bon'] = $request['numero_bon'];
            $data['matricule'] = $request['matricule'];
            $data['acte'] = $request['acte'];
            $data['created_by'] = $created_by;

            $DossierConstante = DossierAssurance::findOrFail($id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $DossierExamen = DossierAssurance::find($id);
        $DossierExamen->delete();
        return response()->json([
            'state'=> 'true',
        ]);
    }
}
