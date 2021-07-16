<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ConfidentialiteAES256CBC;

class Ping extends Controller
{
    //connexion
    public function connexion(){

        $date = Carbon::now()->format('Y-m-d H:i:s');
        $message = 'Connexion ';

        //$messageCrypter = ConfidentialiteAES256CBC::crypterForJs($message);

        $state = true;

        return response()->json([
            'state' => $state,
            'date' => $date,
            'message' => $message
        ]);
    }

}
