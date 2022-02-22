<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //
    public function createProfile(Request $request)
    {
        Auth::guard('api')->user();
        $request->validate([
            'titre' => 'required',
            'description' =>  'required',
        ]);
        $description = $request->description;
        $titre = Str::lower($request->titre);

        $exist= Profile::where('titre', $titre)->get();
        if ($exist->isNotEmpty()) {
            # code...
            return response()->json([
                'state' => false,
                'message' => 'Echec de l\'enregistrement. Ce profile existe déjà en base.'
                ]);
        }else{
            $profile = new Profile();
            $profile->description = $description;
            $profile->titre = $titre;
            $status = $profile->save();
            if($status){
                return response()->json([
                    'state' => true,
                    'message' => 'Enregistrement éffectué.'
                ]);
            }
            else{
                return response()->json([
                    'state' => false,
                    'message' => 'Echec de l\'enregistrement. Réessayer'
                    ]);
            }

        }
    }

    public function getListeProfile()
    {
        $profiles = Profile::all();

        if($profiles->isNotEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $profiles]);
        }
        else{
            return response()->json([
                'state'=> false,
                'message' =>  'Aucun profile actif']);
        }
    }

    public function show($id)
    {
        $profiles = Profile::findOrFail($id);

        if($profiles){
            return response()->json([
                'state'=> true,
                'data' =>  $profiles]);
        }
        else{
            return response()->json([
                'state'=> false,
                'message' =>  'Aucun profile correspondant']);
        }
    }
    public function edit(Request $request, $id)
    {
        Auth::guard('api')->user();
        $request->validate([
            'titre' => 'required',
            'description' =>  'required',
        ]);
        $profil =  Profile::findOrFail($id);
        $profil->description = $request->description;
        $profil->titre = Str::lower($request->titre);
        $profil->save();

        if($profil){
            return response()->json([
                'state'=> true
            ]);
        }
        else{
            return response()->json([
                'state'=> false
            ]);
        }
    }


    public function activerDesactiverProfile(Request $request)
    {
        Auth::guard('api')->user();

        $request->validate([
            'id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

        $id = $request->id;
        $statut = $request->statut;

        $profile = Profile::findOrFail($id) ;

        $data = ['statut' => $statut];

        $statutEnregistrement = $profile->fill($data)->save();

        if($statutEnregistrement){

            return response()->json([
                'state'=> true,
                'message' => 'Opération éffectuée']);
        }
        else{
            return response()->json([
                'state'=> false,
                'message' => 'Opération échouée, réessayer.']);
        }

    }

    public function delete($id){
        $Profile = Profile::find($id);

        $Profile->delete();
        if ($Profile) {
            return response()->json([
                'state'=> true,
            ]);
        } else {
            return response()->json([
                'state'=> false,
            ]);
        }
    }
}
