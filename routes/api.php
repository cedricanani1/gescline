<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Ping;
use App\Http\Controllers\Test;
use App\Http\Controllers\Attribution;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyseController;
use App\Http\Controllers\CompteUtilisateur;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CliniqueController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\DroitsUserController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DossierClientController;
use App\Http\Controllers\CategorieMedicamentController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\ConstanteController;
use App\Http\Controllers\TraitementController;
use App\Http\Controllers\DossierTraitementController;
use App\Http\Controllers\DossierConstanteController;
use App\Http\Controllers\DossierPensementController;
use App\Http\Controllers\DossierExamenController;
use App\Http\Controllers\DossierAssuranceController;
use App\Http\Controllers\DossierDiagnosticController;
use App\Http\Controllers\DossierOrdonnanceController;
use App\Http\Controllers\DossierRendezVousController;
use App\Http\Controllers\FileAttenteController;
use App\Http\Controllers\FactureController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('ping', [Ping::class,'connexion']);
Route::post('connexion', [CompteUtilisateur::class,'connexion']);


Route::middleware('auth:api')->group(function(){

    Route::post('deconnexion', [CompteUtilisateur::class, 'deconnexion']);
    Route::post('inscription', [CompteUtilisateur::class, 'inscription']);
    Route::patch('changerMotDePasse/{id}', [CompteUtilisateur::class, 'changerMotDePasse']);
    Route::put('modifierInformationUtilisateur/{id}', [CompteUtilisateur::class, 'modifierInformationUtilisateur']);
    Route::get('listeUtilisateur', [CompteUtilisateur::class, 'listeUtilisateur']);
    Route::get('utilisateur/{id}', [CompteUtilisateur::class, 'utilisateur']);
    Route::post('activerDesactiverUtilisateur', [CompteUtilisateur::class, 'activerDesactiverUtilisateur']);
    // Route::post('motDePasseOublier', [CompteUtilisateur::class, 'motDePasseOublier']);


   Route::post('creationClinique', [CliniqueController::class, 'creationClinique']);
   Route::get('listClinique', [CliniqueController::class, 'listClinique']);
   Route::get('clinique/{id}', [CliniqueController::class, 'clinique']);
   Route::patch('modifierClinique/{id}', [CliniqueController::class, 'modifierClinique']);
   Route::post('activerDesactiverClinique', [CliniqueController::class, 'activerDesactiverClinique']);
   Route::get('getDepartementsClinique/{clinique}', [CliniqueController::class, 'getDepartementsClinique']);
   Route::get('listeServicesDepartement/{clinique_id}/{departement_id}', [CliniqueController::class, 'listeServicesDepartement']);
   Route::get('getDepartementsNotInClinique/{clinique_id}', [CliniqueController::class, 'getDepartementsNotInClinique']);
   Route::post('updateToDeleteDepartementClinique', [CliniqueController::class, 'updateToDeleteDepartementClinique']);
   Route::post('updateToDeleteDepartementCliniqueService', [CliniqueController::class, 'updateToDeleteDepartementCliniqueService']);
   Route::post('addCliniqueDepartementService', [CliniqueController::class, 'addCliniqueDepartementService']);
   Route::get('getServicesNotInDepartement/{departement_id}', [CliniqueController::class, 'getServicesNotInDepartement']);
   Route::post('attributionAnalysesClinique', [CliniqueController::class, 'attributionAnalysesClinique']);
   Route::post('attributionAssurancesClinique', [CliniqueController::class, 'attributionAssurancesClinique']);



   Route::post('creationDepartement', [DepartementController::class, 'creationDepartement']);
   Route::get('listDepartement', [DepartementController::class, 'listDepartement']);
   Route::get('departement/{id}', [DepartementController::class, 'departement']);
   Route::patch('modifierDepartement/{id}', [DepartementController::class, 'modifierDepartement']);
   Route::post('activerDesactiverDepartement', [DepartementController::class, 'activerDesactiverDepartement']);

   Route::post('creationService', [ServicesController::class, 'creationService']);
   Route::get('listService', [ServicesController::class, 'listService']);
   Route::get('service/{id}', [ServicesController::class, 'service']);
   Route::patch('modifierService/{id}', [ServicesController::class, 'modifierService']);
   Route::post('activerDesactiverService', [ServicesController::class, 'activerDesactiverService']);


   Route::post('creationAnalyse', [AnalyseController::class, 'creationAnalyse']);
   Route::get('listAnalyses', [AnalyseController::class, 'listAnalyses']);
   Route::get('listAnalysesEmpty/{clinique_id}', [AnalyseController::class, 'listAnalysesEmpty']);
   Route::get('analyse/{id}', [AnalyseController::class, 'analyse']);
   Route::patch('modifierAnalyse/{id}', [AnalyseController::class, 'modifierAnalyse']);
   Route::post('activerDesactiverAnalyse', [AnalyseController::class, 'activerDesactiverAnalyse']);


   Route::post('creationAssurance', [AssuranceController::class, 'creationAssurance']);
   Route::get('listAssurances', [AssuranceController::class, 'listAssurances']);
   Route::get('listAssurancesEmpty/{clinique_id}', [AssuranceController::class, 'listAssurancesEmpty']);
   Route::get('assurance/{id}', [AssuranceController::class, 'assurance']);
   Route::patch('modifierAssurance/{id}', [AssuranceController::class, 'modifierAssurance']);
   Route::post('activerDesactiverAssurance', [AssuranceController::class, 'activerDesactiverAssurance']);


   Route::post('attribuerDepartementsClinique', [Attribution::class, 'departementsClinique']);
   Route::post('attribuerServicesDepartement/{id}', [Attribution::class, 'servicesDepartement']);
   Route::post('lieuDeTravail', [Attribution::class, 'lieuDeTravail']);
   Route::post('activerDesactiverServicesDepartement', [Attribution::class, 'activerDesactiverServicesDepartement']);
   Route::post('activerDesactiverDepartementsClinique', [Attribution::class, 'activerDesactiverDepartementsClinique']);
   Route::post('activerDesactiverlieuDeTravail', [Attribution::class, 'activerDesactiverlieuDeTravail']);
   Route::post('activerDesactiverCliniqueAnanlyse', [Attribution::class, 'activerDesactiverCliniqueAnanlyse']);
   Route::post('activerDesactiverCliniqueAssurance', [Attribution::class, 'activerDesactiverCliniqueAssurance']);


   Route::post('createWorkflow', [WorkflowController::class, 'createWorkflow']);
   Route::get('getWorkflow/{clinique}', [WorkflowController::class, 'getWorkflow']);
   Route::post('deleteWorkflow', [WorkflowController::class, 'deleteWorkflow']);

   Route::post('createProfile', [ProfileController::class, 'createProfile']);
   Route::get('getListeProfile', [ProfileController::class, 'getListeProfile']);
   Route::post('activerDesactiverProfile', [ProfileController::class, 'activerDesactiverProfile']);



   Route::get('getListeModule', [DroitsUserController::class, 'getListeModule']);
   Route::get('getUserPermission', [DroitsUserController::class, 'getUserPermission']);
   Route::post('assignerPermission', [DroitsUserController::class, 'droitDefault']);

   // ROUTE PATIENT
   Route::post('ajouterPatient', [ClientController::class, 'store']);
   Route::get('listerPatients', [ClientController::class, 'index']);
   Route::get('patient/{id}', [ClientController::class, 'show']);
   Route::put('patient/{id}', [ClientController::class, 'update']);
   Route::delete('patient/{id}', [ClientController::class, 'delete']);
   Route::post('ajouterNouveauDossier', [DossierClientController::class, 'store']);

   // ROUTE CATEGORIE DE MEDICAMENT

   Route::post('ajouterCategorieMedoc', [CategorieMedicamentController::class, 'store']);
   Route::get('listerCategorieMedoc', [CategorieMedicamentController::class, 'index']);
   Route::get('categorieMedoc/{id}', [CategorieMedicamentController::class, 'show']);
   Route::put('categorieMedoc/{id}', [CategorieMedicamentController::class, 'update']);
   Route::delete('categorieMedoc/{id}', [CategorieMedicamentController::class, 'delete']);

    // ROUTE MEDICAMENT

   Route::post('ajouterMedoc', [MedicamentController::class, 'store']);
   Route::get('listerMedoc', [MedicamentController::class, 'index']);
   Route::get('medoc/{id}', [MedicamentController::class, 'show']);
   Route::put('medoc/{id}', [MedicamentController::class, 'update']);
   Route::delete('medoc/{id}', [MedicamentController::class, 'delete']);

    // ROUTE DIAGNOSTIC

   Route::post('ajouterDiagnostic', [DiagnosticController::class, 'store']);
   Route::get('listeDesDiagnostics', [DiagnosticController::class, 'index']);
   Route::get('diagnostic/{id}', [DiagnosticController::class, 'show']);
   Route::put('diagnostic/{id}', [DiagnosticController::class, 'update']);
   Route::delete('diagnostic/{id}', [DiagnosticController::class, 'delete']);

    // ROUTE EXAMEN

   Route::post('ajouterExamen', [ExamenController::class, 'store']);
   Route::get('listeDesExamens', [ExamenController::class, 'index']);
   Route::get('examen/{id}', [ExamenController::class, 'show']);
   Route::put('examen/{id}', [ExamenController::class, 'update']);
   Route::delete('examen/{id}', [ExamenController::class, 'delete']);

    // ROUTE CONSTANTE

   Route::post('ajouterConstante', [ConstanteController::class, 'store']);
   Route::get('listeDesConstantes', [ConstanteController::class, 'index']);
   Route::get('constante/{id}', [ConstanteController::class, 'show']);
   Route::put('constante/{id}', [ConstanteController::class, 'update']);
   Route::delete('constante/{id}', [ConstanteController::class, 'delete']);

    // ROUTE TRAITEMENT URGENCE

   Route::post('ajouterTraitementUrgence', [TraitementController::class, 'store']);
   Route::get('listeDesTraitementsUrgence', [TraitementController::class, 'index']);
   Route::get('traitementUrgence/{id}', [TraitementController::class, 'show']);
   Route::put('traitementUrgence/{id}', [TraitementController::class, 'update']);
   Route::delete('traitementUrgence/{id}', [TraitementController::class, 'delete']);

    // ROUTE  DOSSIER TRAITEMENT

   Route::post('ajouterTraitementDossier', [DossierTraitementController::class, 'store']);
   Route::get('listeDesTraitementsDossier', [DossierTraitementController::class, 'index']);
   Route::get('traitementDossier/{id}', [DossierTraitementController::class, 'show']);
   Route::put('traitementDossier/{id}', [DossierTraitementController::class, 'update']);
   Route::delete('traitementDossier/{id}', [DossierTraitementController::class, 'delete']);

    // ROUTE DOSSIER CONSTANTE

    Route::post('ajouterConstanteDossier', [DossierConstanteController::class, 'store']);
    Route::get('listeConstanteDossiers', [DossierConstanteController::class, 'index']);
    Route::get('constanteDossier/{id}', [DossierConstanteController::class, 'show']);
    Route::put('constanteDossier/{id}', [DossierConstanteController::class, 'update']);
    Route::delete('constanteDossier/{id}', [DossierConstanteController::class, 'delete']);

    // ROUTE DOSSIER PENSEMENT

    Route::post('ajouterPensementDossier', [DossierPensementController::class, 'store']);
    Route::get('listePensementDossiers', [DossierPensementController::class, 'index']);
    Route::get('pensementDossier/{id}', [DossierPensementController::class, 'show']);
    Route::put('pensementDossier/{id}', [DossierPensementController::class, 'update']);
    Route::delete('pensementDossier/{id}', [DossierPensementController::class, 'delete']);

    // ROUTE DOSSIER EXAMEN

    Route::post('ajouterExamenDossier', [DossierExamenController::class, 'store']);
    Route::get('listeExamenDossiers', [DossierExamenController::class, 'index']);
    Route::get('examenDossier/{id}', [DossierExamenController::class, 'show']);
    Route::post('modifierExamenDossier', [DossierExamenController::class, 'update']);
    Route::delete('deleteExamenDossier/{id}', [DossierExamenController::class, 'delete']);

    // ROUTE DOSSIER EXAMEN

    Route::post('ajouterAssuranceDossier', [DossierAssuranceController::class, 'store']);
    Route::get('listeAssuranceDossiers', [DossierAssuranceController::class, 'index']);
    Route::get('AssuranceDossier/{id}', [DossierAssuranceController::class, 'show']);
    Route::post('modifierAssuranceDossier/{id}', [DossierAssuranceController::class, 'update']);
    Route::delete('deleteAssuranceDossier/{id}', [DossierAssuranceController::class, 'destroy']);

    // ROUTE DOSSIER DIAGNOSTIC

    Route::post('ajouterDiagnosticDossier', [DossierDiagnosticController::class, 'store']);
    Route::get('listeDiagnosticDossiers', [DossierDiagnosticController::class, 'index']);
    Route::get('diagnosticDossier/{id}', [DossierDiagnosticController::class, 'show']);
    Route::put('modifierDiagnosticDossier/{id}', [DossierDiagnosticController::class, 'update']);
    Route::delete('deleteDiagnosticDossier/{id}', [DossierDiagnosticController::class, 'delete']);

    // ROUTE DOSSIER ORDONNANCE

    Route::post('ajouterOrdonnanceDossier', [DossierOrdonnanceController::class, 'store']);
    Route::get('listeOrdonnanceDossiers', [DossierOrdonnanceController::class, 'index']);
    Route::get('ordonnanceDossier/{id}', [DossierOrdonnanceController::class, 'show']);
    Route::put('modifierOrdonnanceDossier/{id}', [DossierOrdonnanceController::class, 'update']);
    Route::delete('deleteOrdonnanceDossier/{id}', [DossierOrdonnanceController::class, 'delete']);

    // ROUTE DOSSIER RENDEZ VOUS

    Route::post('ajouterRendezVousDossier', [DossierRendezVousController::class, 'store']);
    Route::get('listeRendezVousDossiers', [DossierRendezVousController::class, 'index']);
    Route::get('rendezVousDossier/{id}', [DossierRendezVousController::class, 'show']);
    Route::put('modifierRendezVousDossier/{id}', [DossierRendezVousController::class, 'update']);
    Route::delete('deleteRendezVousDossier/{id}', [DossierRendezVousController::class, 'delete']);

    // ROUTE DOSSIER FILE D'ATTENTE

    Route::get('listeFileAttentes', [FileAttenteController::class, 'index']);
    Route::get('fileAttente/{id}', [FileAttenteController::class, 'show']);
    Route::put('modifierFileAttente/{id}', [FileAttenteController::class, 'update']);
    Route::delete('deleteFileAttente/{id}', [FileAttenteController::class, 'delete']);

    // ROUTE DOSSIER FACTURE

    Route::get('getDossierFacture/{id}', [FactureController::class, 'show']);

});


/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/




