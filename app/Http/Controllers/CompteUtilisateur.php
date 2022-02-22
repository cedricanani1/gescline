<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Lieu_de_travail;
use App\Models\Profile;
use App\Models\ProfileUser;
use App\Models\WorkTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\sendUserCompteInfo;
use Carbon\Carbon;

class CompteUtilisateur extends Controller
{
    //

    public function connexion(Request $request){

        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        //return response()->json(['message' => "Votre compte est fermé, veuillez contacter l'administrateur."]);
        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];
        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            //return $user;
            if ($user->statut == "actif") {
                # code...
                $token = $user->createToken('authToken')->accessToken;
                //$droits = Droits::getUserDroit($user->id);
                $created_by = $user->id;
                $work['user_id'] = $created_by;
                $work['start_hour'] = Carbon::now()->format('H:i');
                $work['label'] = 'connecté';
                WorkTime::create($work);

                return response()->json([
                    'state' => true,
                    'token' => $token,
                    'user' => auth()->user()]);
            }
            else{
                return response()->json([
                    'state' => false,
                    'message' => "Votre compte est fermé, veuillez contacter l'administrateur."]);
            }
        }
        else {
            return response()->json([
                'state' => false,
                'message' => 'Identifiants incorrects']);
        }

    }

    public function listeUtilisateur(){
        Auth::guard('api')->user();

       // $utilisateurs = User::where('statut','actif')->get();
       $utilisateurs = User::with('profile')->get();

        if(!$utilisateurs->isEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $utilisateurs]);
        }
        else{
            return response()->json([
                'state'=> false,
                'data' =>  $utilisateurs]);
        }
    }

    public function utilisateur($id){
        Auth::guard('api')->user();

        $utilisateur = User::with('permissions','profile','service')->where('statut','actif')
                            ->where('id',$id)
                            ->first();

        if($utilisateur){
            return response()->json([
                'state'=> true,
                'data' =>  $utilisateur]);
        }
        else{
            return response()->json([
                'state'=> false,
                'data' =>  $utilisateur]);
        }
    }

    public function inscription(Request $request){

        Auth::guard('api')->user();

         $request->validate([
            'nom' => 'required',
            'prenoms' => 'required',
            'nationalite' => 'required',
            'telephone' => 'required',
            'date_naissance' => 'required',
            'adresse_domicile' => 'required',
            'situation_matrimoniale' => 'required',
            'genre' => 'required',
           // 'email' => 'email|required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
            'clinique_id' => 'required',
            'departement_id' => 'required',
            'service_id' => 'required',
            'profile_id' => 'required'
        ]);

        $nom = strtoupper($request->nom);
        $prenoms = ucwords($request->prenoms);
        $nationalite = ucwords($request->nationalite);
        $telephone = $request->telephone;
        $date_naissance = Carbon::parse($request->date_naissance)->format('Y-m-d');
        $adresse_domicile = ucwords($request->adresse_domicile);
        $situation_matrimoniale = strtolower($request->situation_matrimoniale);
        $genre = strtolower($request->genre);
        $email = $request->email;
        //$password = strtoupper(Str::random(3)).strtolower(Str::random(4)).strtoupper(Str::random(3));
        //$password = Hash::make($password);
        $password = Hash::make($request->password);
        $role = ucwords($request->role);
        $clinique_id = $request->clinique_id;
        $departement_id = $request->departement_id;
        $service_id = $request->service_id;



        $utilisateur = new User();

        $utilisateur->nom = $nom;
        $utilisateur->prenoms = $prenoms;
        $utilisateur->nationalite = $nationalite;
        $utilisateur->telephone = $telephone;
        $utilisateur->date_naissance = $date_naissance;
        $utilisateur->adresse_domicile = $adresse_domicile;
        $utilisateur->situation_matrimoniale = $situation_matrimoniale;
        $utilisateur->genre = $genre;
        $utilisateur->email = $email;
        $utilisateur->password = $password;
        $utilisateur->role = $role;

        $enregistrement = $utilisateur->save();

        if (count($request->profile_id) > 0) {

            foreach ($request->profile_id as $key =>  $value) {

                $profile = Profile::findOrFail($value);
                # code...
                $userProfile = new ProfileUser();
                $userProfile->profile_id = $profile->id;
                $userProfile->user_id = $utilisateur->id;
                $userProfile->save();
            }

        }

        if($enregistrement){

           // $details = ['password' => $password, 'email' => $utilisateur->email];
            //$utilisateur->notify( new sendUserCompteInfo($details));

            $lieu_de_travail = new Lieu_de_travail();

            $lieu_de_travail->clinique_id = $clinique_id;
            $lieu_de_travail->departement_id = $departement_id;
            $lieu_de_travail->service_id = $service_id;
            $lieu_de_travail->user_id = $utilisateur->id;

            $exist = Lieu_de_travail::where('clinique_id',$lieu_de_travail->clinique_id)
                                    -> where('departement_id',$lieu_de_travail->departement_id)
                                    -> where('service_id',$lieu_de_travail->service_id)
                                    -> where('user_id',$lieu_de_travail->user_id)
                                    ->get();


            if($exist->isEmpty()){
                $enregistrement = $lieu_de_travail->save();

                if($enregistrement){
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
            else{
                return response()->json([
                    'state'=> false,
                    'message' => 'Cette utilisateur à déjà un lieu de travail']);
            }

            /*return response()->json([
                'state' => true,
                'message' => 'Enregistrement éffectué.'
                ]);*/
        }
        else{
            return response()->json([
                'state' => false,
                'message' => 'Echec de l\'enregistrement. Réessayer'
                ]);
        }



    }

    public function modifierInformationUtilisateur(Request $request, $id){
            $utilisateur = User::findOrFail($id) ;

            // return response()->json($utilisateur);
         $request->validate([
            'nom' => 'required',
            'prenoms' => 'required',
            'nationalite' => 'required',
            'telephone' => 'required',
            'date_naissance' => 'required',
            'adresse_domicile' => 'required',
            'situation_matrimoniale' => 'required',
            'genre' => 'required',
            'email' => 'required',
            //'password' => 'required',
            'role' => 'required'
        ]);
        $nom = strtoupper($request->nom);
        $prenoms = ucwords($request->prenoms);
        $nationalite = ucwords($request->nationalite);
        $telephone = $request->telephone;
        $date_naissance = $request->date_naissance;
        $adresse_domicile = ucwords($request->adresse_domicile);
        $situation_matrimoniale = strtolower($request->situation_matrimoniale);
        $genre = strtolower($request->genre);
        $email = $request->email;
        //$password = strtoupper(Str::random(3)).strtolower(Str::random(4)).strtoupper(Str::random(3));
        //$password = Hash::make($password);
        //$password = Hash::make($request->password);
        $role = ucwords($request->role);

        $data = [   'nom' => $nom,
                    'prenoms' => $prenoms,
                    'nationalite' => $nationalite,
                    'telephone' => $telephone,
                    'date_naissance' => $date_naissance,
                    'adresse_domicile' => $adresse_domicile,
                    'situation_matrimoniale' => $situation_matrimoniale,
                    'genre' => $genre,
                    'email' => $email,
                    'role' => $role,
        ];
        $statut = $utilisateur->fill($data)->save();
        if (count($request->profile_id) > 0) {
            $profileUsers = ProfileUser::where('user_id',$utilisateur->id)->get();

            foreach ($profileUsers as  $profileUser) {
                $profileUser->delete();
            }

            foreach ($request->profile_id as  $value) {

                $profile = Profile::findOrFail($value);
                $userProfile = new ProfileUser();
                $userProfile->profile_id = $profile->id;
                $userProfile->user_id = $utilisateur->id;
                $userProfile->save();

            }

        }

            $user_id = Auth::guard('api')->user()->id;
             $lieu_de_travail =  Lieu_de_travail::where('user_id',$id)->first();
             if ($lieu_de_travail) {
                $lieu_de_travail->clinique_id = $request['clinique_id'];
                $lieu_de_travail->departement_id = $request['departement_id'];
                $lieu_de_travail->service_id = $request['service_id'];

                $lieu_de_travail->save();
             }




        if($statut){

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


    public function changerMotDePasse(Request $request, $id){

        Auth::guard('api')->user();
        $utilisateur = User::findOrFail($id) ;

         $request->validate([
            'password' => 'required'
        ]);

        $nouveauMotDePasse = Hash::make($request->password);

        $data = ['password' => $nouveauMotDePasse];

        $statut= $utilisateur->fill($data)->save();

       if($statut){

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

    public function motDePasseOublier(Request $request){

        Auth::guard('api')->user();

        $request->validate([
            'email' => 'email|required'
        ]);


        $email = $request->email;
        $password = strtoupper(Str::random(3)).strtolower(Str::random(4)).strtoupper(Str::random(3));
        $nouveauMotDePasse = Hash::make($password);

        $utilisateur = User::where('email',$email)->first() ;
        $data = ['password' => $nouveauMotDePasse];

        $statut = $utilisateur->fill($data)->save();

        if($statut){
          $details = ['password' => $password, 'email' => $utilisateur->email];
          $utilisateur->notify( new sendUserCompteInfo($details));
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

    public function activerDesactiverUtilisateur(Request $request){
        Auth::guard('api')->user();

        $request->validate([
            'id' => 'required',
            'statut' => 'String|required|in:actif,inactif'
        ]);

        $id = $request->id;
        $statut = $request->statut;

        $utilisateur = User::findOrFail($id) ;

        $data = ['statut' => $statut];

        $statutEnregistement = $utilisateur->fill($data)->save();

        if($statutEnregistement){

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

    public function deconnexion(Request $request){
                $created_by = $request->user()->id;
                $work['user_id'] = $created_by;
                $work['start_hour'] = Carbon::now()->format('H:i');
                $work['end_hour'] = Carbon::now()->format('H:i');
                $work['label'] = 'deconnecté';
                WorkTime::create($work);

                $token = $request->user()->token();
                $token->revoke();

        return response()->json([
            'state' => true,
            'message' => 'Votre etes deconnecté']);
    }


}
