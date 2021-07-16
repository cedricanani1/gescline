<?php

namespace App\Http\Controllers;

use App\Models\Droit;
use App\Models\Module;
use Illuminate\Http\Request;

class DroitsUserController extends Controller
{
    //
    public static function droitDefault(Request $request)
    {


        foreach ($request->modules as $value) {
            $module = Module::where('libelle', $value['libelle'])->first();

            $data['user_id']=$value['user_id'];
            $data['module_id'] = $module->id;
            $data['create'] = $value['create_permission'];
            $data['read']= $value['read_permission'];
            $data['write']= $value['write_permission'];
            $data['update'] = $value['update_permission'];
            $data['delete'] = $value['delete_permission'];
            $data['import'] = $value['import_permission'];
            $data['export'] = $value['export_permission'];
            $data['transfert'] = $value['transfert_permission'];
            $data['assigner'] = $value['assign_permission'];
            $droit = Droit::where('user_id', $value['user_id'])->where('module_id', $module->id)->first();
            if (empty($droit)) {
                $status = Droit::create($data);
            }else{
                $status = $droit->fill($data)->save();
            }
        }

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

    public static function droitAdministration($user_id, $create, $read, $update, $delete, $import, $export, $transfert, $assigner)
    {
        $module = Module::where('libelle', 'administration')->first();

        $data['user_id']=$user_id;
        $data['module_id'] = $module->id;
        $data['create'] = $create;
        $data['read']= $read;
        $data['update'] = $update;
        $data['delete'] = $delete;
        $data['import'] = $import;
        $data['export'] = $export;
        $data['transfert'] = $transfert;
        $data['assigner'] = $assigner;
        $droit = Droit::where('user_id', $user_id)->where('module_id', $module->id)->first();
        if (empty($droit)) {
            # code...
            $status = Droit::create($data);
        }else{
            $status = $droit->fill($data)->save();
        }

        if ($status) {
            # code...
            return response()->json([
                'state'=> 'true',
            ]);

        }else{
            return response()->json([
                'state'=> false,
            ]);
        }
    }

    public function getListeModule()
    {
        $modules = Module::all();

        if($modules->isNotEmpty()){
            return response()->json([
                'state'=> true,
                'data' =>  $modules]);
        }
        else{
            return response()->json([
                'state'=> false,
                'data' =>  $modules]);
        }
    }
}
