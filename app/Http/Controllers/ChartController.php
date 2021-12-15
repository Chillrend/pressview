<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class ChartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function render(){
        return view('chart');
    }

    public function getByJurusan(){
        $client = new Client([
            'base_uri' => 'http://press.pnj.ac.id'
        ]);
        $response = $client->get('http://press.pnj.ac.id/?rest_route=/wp/v2/categories&per_page=100');
        if($response->getStatusCode() > 199 && $response->getStatusCode() < 300){
            $responseArray = json_decode($response->getBody());
        }else{
            return response()->json(['message' => 'Not Found!'], 404);
        }
        $labels = [];
        $data = [];
        for ($i = 0; $i < count($responseArray); $i++){
            $categories = $responseArray[$i];
            if (strpos($categories->name, 'Buku') === false && strpos($categories->name, 'PNJ') === false && strpos($categories->name, 'MITRA') === false){
                array_push($labels, $categories->name);
                array_push($data, $categories->count);
            }
        }

        $formattedResponse = [
            'labels' => $labels,
            'data' =>$data
        ];

        return response()->json($formattedResponse);
    }

    public function getByYear(){
        $client = new Client([
            'base_uri' => 'http://press.pnj.ac.id'
        ]);
        $response = $client->get('http://press.pnj.ac.id/?rest_route=/wp/v2/categories&per_page=100');
        if($response->getStatusCode() > 199 && $response->getStatusCode() < 300){
            $responseArray = json_decode($response->getBody());
        }else{
            return response()->json(['message' => 'Not Found!'], 404);
        }
        $labels = [];
        $data = [];
        for ($i = 0; $i < count($responseArray); $i++){
            $categories = $responseArray[$i];
            if (strpos($categories->name, '20') ==! false){
                array_push($labels, $categories->name);
                array_push($data, $categories->count);
            }
        }

        $formattedResponse = [
            'labels' => $labels,
            'data' =>$data
        ];

        return response()->json($formattedResponse);
    }

    public function wordCloud(){
        $client = new Client([
            'base_uri' => 'http://press.pnj.ac.id'
        ]);
        $response = $client->get('http://press.pnj.ac.id/?rest_route=%2Fwp%2Fv2%2Ftags&per_page=100');
        if($response->getStatusCode() > 199 && $response->getStatusCode() < 300){
            $responseArray = json_decode($response->getBody());
        }else{
            return response()->json(['message' => 'Not Found!'], 404);
        }
        $data = [];
        for ($i = 0; $i < count($responseArray); $i++){

            $item = [$responseArray[$i]->name, 16];
            array_push($data, $item);
        }

        return response()->json($data);
    }
}
