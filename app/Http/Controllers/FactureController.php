<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DossierClient;
use App\Models\Facture;
use App\Models\FileAttente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;

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
            $request->validate([
                'num_facture' => 'string|required',
                'dossier_id' => 'string|required',
                'sold' => 'boolean|required',
            ]);

            $data['num_facture'] = $request['num_facture'];
            $data['dossier_id']=$request['dossier_id'];
            $data['sold']=$request['sold'];

            $status = Facture::create($data);

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
        $dossier = DossierClient::with('examens','pensements','ordonnances','consultation')->findOrFail($id);
        $facture = Facture::where('dossier_id',$dossier->id)->get();

            if (count($facture) === 0 ) {
                $num= Carbon::now()->format('YmdHi');
                $data['num_facture'] = $num;
                $data['dossier_id']=$id;
                $data['sold']=0;
                $status = Facture::create($data);
            }

        $cumulAssurance= 0 ;
        $cumulpaye = 0;
        $cons = 0;
        foreach ($dossier->consultation as  $consult) {
            if ($consult->pivot->purchased == 0 || $consult->pivot->purchased == null) {
                if ((Boolean)$consult->pivot->assurance) {
                    $consult->prixAssurance =  (( (int)$consult->prix * $dossier->assurance[0]->pivot->pourcentage) /100);
                    // $cons = $cons + ((int)$consult->prix);
                    $cumulAssurance = $cumulAssurance + ($consult->prixAssurance);
                }else{
                    // $cons = $cons + (int)$consult->prix;
                    $consult->prixAssurance = 0;
                }
            } else{
                if ((Boolean)$consult->pivot->assurance) {
                    $consult->prixAssurance =  (( (int)$consult->prix * $dossier->assurance[0]->pivot->pourcentage) /100);
                    $cumulAssurance = $cumulAssurance + ($consult->prixAssurance);
                    $cumulpaye = $cumulpaye + ($consult->prixAssurance);
                }else{
                    $consult->prixAssurance = 0;
                    $cumulpaye = $cumulpaye + ((int)$consult->prix);
                }
                // $cumulpaye = $cumulpaye + ((int)$consult->prix);
            }
            $cons = $cons + ((int)$consult->prix);
        }
        $dossier->consultationCost = $cons;



        $pst = 0;
        foreach ($dossier->pensements as  $pensement) {
            if ($pensement->pivot->purchased == 0 || $pensement->pivot->purchased == null) {
                if ((Boolean)$pensement->pivot->assurance) {
                    $pensement->prixAssurance =  (( (int)$pensement->prix * $dossier->assurance[0]->pivot->pourcentage) /100);
                    // $pst = $pst + ((int)$pensement->prix );
                    $cumulAssurance = $cumulAssurance + ($pensement->prixAssurance);

                }else{
                    // $pst = $pst + (int)$pensement->prix;
                    $pensement->prixAssurance = 0;
                }
            }else {
                if ((Boolean)$pensement->pivot->assurance) {
                    $pensement->prixAssurance =  (( (int)$pensement->prix * $dossier->assurance[0]->pivot->pourcentage) /100);
                    $cumulpaye = $cumulpaye + ($pensement->prixAssurance);
                }else{
                    $pensement->prixAssurance = 0;
                    $cumulpaye = $cumulpaye + ((int)$pensement->prix);
                }
            }
            // total assurance
            $pst = $pst + ((int)$pensement->prix );
        }

        $dossier->pensementCost = $pst;

        $exam = 0;
        // return $dossier->assurance;
        foreach ($dossier->examens as  $examen) {
            if ($examen->pivot->purchased == 0 || $examen->pivot->purchased == null) {
                if ((Boolean)$examen->pivot->assurance) {
                    $examen->prixAssurance =  (( (int)$examen->prix * $dossier->assurance[0]->pivot->pourcentage) /100);
                    // $exam = $exam + ((int)$examen->prix);
                    $cumulAssurance = $cumulAssurance + ($examen->prixAssurance);
                }else{
                    // $exam = $exam + (int)$examen->prix;
                    $examen->prixAssurance = 0;
                }

            } else{
                if ((Boolean)$examen->pivot->assurance) {
                    $examen->prixAssurance =  (( (int)$examen->prix * $dossier->assurance[0]->pivot->pourcentage) /100);
                    $cumulAssurance = $cumulAssurance + ($examen->prixAssurance);
                    $cumulpaye = $cumulpaye + ((int)$examen->prix);
                }else{
                    $examen->prixAssurance = 0;
                    $cumulpaye = $cumulpaye + ((int)$examen->prix);
                }
            }
            $exam = $exam + ((int)$examen->prix);
        }
        $dossier->examenCost = $exam;

        $ord = 0;
        foreach ($dossier->ordonnances as  $orden) {

            foreach ($orden->medicaments as $medicament) {

                if ($medicament->pivot->purchased == 0 || $medicament->pivot->purchased == null) {
                    // if ((Boolean)$medicament->pivot->assurance) {
                        // $ord += ($medicament->prix * $medicament->pivot->quantity);

                        // $cumulAssurance = $cumulAssurance + ($medicament->prixAssurance);
                    // }else{

                    // }
                }else{
                    $cumulpaye = $cumulpaye + ((int)$medicament->prix * $medicament->pivot->quantity);
                }
                $ord += ($medicament->prix * $medicament->pivot->quantity);

            }

            $orden->medicaments;
        }

        $dossier->ordornnanceCost = $ord;
        $dossier->client;

        $total = $exam + $ord + $pst +$cons;
        $dossier->total = $total;
        $dossier->cumulPaye =  $cumulpaye;
        $dossier->assurance->first();
        $facture = Facture::where('dossier_id',$dossier->id)->first();
        $facture->montant_total = $total;
        $facture->montant =($total-$cumulAssurance) - $cumulpaye;
        if ($facture->montant == 0) {
            $facture->sold = 1;
        }
        $facture->save();
        if (count($dossier->assurance) > 0) {
            $dossier->total_assurance = $cumulAssurance;
        }else{
            $dossier->total_assurance = 0;
        }
        $dossier->factures;
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
    }

    public function caisse(Request $request)
    {
            $created_by = Auth::guard('api')->user()->id;
            $request->validate([
                'dossier_id' => 'integer|required',
                'num_facture' => 'integer|required',
            ]);
            $DossierExamen = Facture::where('dossier_id',$request['dossier_id'])->where('num_facture',$request['num_facture'])->first();

            $DossierExamen->sold = $request['sold'];

            FileAttente::where('dossier_id',$request['dossier_id'])->first();
            $dossier = DossierClient::with('examens','pensements','ordonnances','consultation')->findOrFail($request['dossier_id']);

            foreach ($dossier->consultation as  $consult) {
                if ($consult->pivot->purchased == 0 || $consult->pivot->purchased == null) {
                    $consult->pivot->purchased = 1;
                    $consult->pivot->save();
                }

            }

            foreach ($dossier->pensements as  $pensement) {
                if ($pensement->pivot->purchased == 0 || $pensement->pivot->purchased == null) {
                    $pensement->pivot->purchased = 1;
                    $pensement->pivot->save();
                }
            }

            foreach ($dossier->examens as  $examen) {
                if ($examen->pivot->purchased == 0 || $examen->pivot->purchased == null) {
                    $examen->pivot->purchased = 1;
                    $examen->pivot->save();

                }
            }

            foreach ($dossier->ordonnances as  $orden) {

                foreach ($orden->medicaments as $medicament) {

                    if ($medicament->pivot->purchased == 0 || $medicament->pivot->purchased == null) {
                        $medicament->pivot->purchased= 1;
                        $medicament->pivot->save();
                    }
                }
            }
            $dossier->save();
            $status = $DossierExamen->save();
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
