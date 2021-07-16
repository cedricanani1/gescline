<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierClient;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dossier = DossierClient::with('constantes','pensements','examens','traitements','assurance','diagnostics','ordonnances','rendezVous','fileAttente')->findOrFail($id);

        $pst = 0;
        foreach ($dossier->pensements as  $pensement) {
            $pst = $pst + $pensement->prix;
        }

        $dossier->pensementCost = $pst;

        $exam = 0;
        foreach ($dossier->examens as  $examen) {
            $exam = $pst + $examen->prix;
        }
        $dossier->examenCost = $exam;

        $ord = 0;
        foreach ($dossier->ordonnances as  $orden) {

            $orden->medicaments;
            foreach ($orden->medicaments as $medicament) {
                $ord = $ord + $medicament->prix;
            }
            $dossier->ordornnanceCost = $ord;
        }


        return response()->json($dossier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
