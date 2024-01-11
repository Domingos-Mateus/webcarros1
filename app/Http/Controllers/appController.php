<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class appController extends Controller
{
    //

    public function verificar_cep($cep){

        $client = new Client();
        $url = 'https://viacep.com.br/ws/'. $cep .'/json/';


        try {
            $response = Http::withHeaders([
                'Content-type'  => 'application/json; charset=utf-8',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer 06aef429-a981-3ec5-a1f8-71d38d86481e',
            ])->get($url);

            $status = $response->status();

            if ($status != 200) {
                return $response;
            }

            $resultado = json_decode($response->getBody()->getContents());

            return $resultado;

        } catch (\Exception $e) {
            // tratar erro aqui
            return $e;
        }


    }


    public function verificar_cnpj($cnpj){

        $client = new Client();
        $url = "https://www.receitaws.com.br/v1/cnpj/${cnpj}";



        try {
            $response = Http::withHeaders([
                'Content-type'  => 'application/json; charset=utf-8',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer 06aef429-a981-3ec5-a1f8-71d38d86481e',
            ])->get($url);

            $status = $response->status();

            if ($status != 200) {
                return $response;
            }

            $resultado = json_decode($response->getBody()->getContents());

            return $resultado;

        } catch (\Exception $e) {
            // tratar erro aqui
            return $e;
        }


    }

}
