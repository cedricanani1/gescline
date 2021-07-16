<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    //
    public function getWorkflow($clinique)
    {
        $services_sources = DB::table('workflows')
                            ->where('clinique_id', $clinique)
                            ->select('source_service_id')
                            ->distinct()
                            ->get();

        $data = collect([]);
        foreach ($services_sources as  $services_source) {
            # code...
            $source = Service::findOrFail($services_source->source_service_id);
            $destinations = DB::table('workflows')
                            ->where('source_service_id', $services_source->source_service_id)
                            ->join('services', 'services.id', 'workflows.destination_service_id')
                            ->select('services.*')
                            ->get();
            $data = $data->push([
                'source' => $source,
                'destinations' => $destinations
            ]);
        }
        return response()->json([
            'state'=> true,
            'data' => $data]);

    }

    public function createWorkflow(Request $request)
    {
        Auth::guard('api')->user();
        $request->validate([
            'source_service_id' => 'required',
            'clinique_id' =>  'required',
            'commentaire' => 'nullable'
        ]);

        $sources = $request->source_service_id;
        foreach ($sources as  $source) {
            # code...
            $destinations = $source['destination_service_id'];
            foreach ($destinations as $destination) {
                # code...
                $workflow = new workflow();
                $workflow->source_service_id = $source['source_id'];
                $workflow->destination_service_id = $destination['id'];
                $workflow->clinique_id = $request->clinique_id;
                $workflow->commentaire = $request->commentaire;
                $workflow->save();
            }
        }


        return response()->json([
            'state'=> true,
            'message' => 'Opération éffectuée']);
    }

    public function deleteWorkflow(Request $request)
    {
        Auth::guard('api')->user();
        $request->validate([
            'source_service_id' => 'required',
            'clinique_id' =>  'required',
            'destination_service_id' => 'required'
        ]);
        $workflow = workflow::where('source_service_id', $request->source_service_id)->where('clinique_id', $request->clinique_id)
                    ->where('destination_service_id', $request->destination_service_id)->first();

        if (!empty($workflow)) {
            # code...
            $workflow->delete();
            return response()->json([
                'state'=> true,
                'message' => 'Opération éffectuée']);
        }else{
            return response()->json([
                'state'=> false,
                'message' => 'Workflow list not found !']);
        }

    }
}
