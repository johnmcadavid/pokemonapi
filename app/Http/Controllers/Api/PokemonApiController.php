<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PokemonApiController extends Controller
{
    /**
     *
     * @param  Client $client
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getPokemons(Client $client, Request $request)
    {
        $limit = !empty($request->limit) && is_int($request->limit) ? $request->limit : 100;
        $offset = !empty($request->offset) && is_int($request->offset) ? $request->offset : 0;
        
        $response = $client->request('GET', "pokemon?limit=$limit&offset=$offset");
        $data = $response->getBody();
        
        return $data;
    }

    /**
     *
     * @param Request $request
     * @return string
     */
    public function getAppend(Request $request)
    {
        switch($request->param) {
            case('ability'):
                $append = !empty($request->value) && is_int($request->value) ? "ability/$request->value" : "ability/0";
                break;

            case('type'):
                $append = !empty($request->value) && is_int($request->value) ? "type/$request->value" : "type/0";
                break;
            
            case('pokemon'):
                $append = !empty($request->value) ? "pokemon/$request->value" : "pokemon/0";
                break;

            default:
                $append = "pokemon/0";        
        }

        return $append;
    }    


    /**
     *
     * @param Client $client
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function findPokemons(Client $client, Request $request)
    {
        $append = $this->getAppend($request);
        $response = $client->request('GET', $append);
        $data = $response->getBody();
        return $data;
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function testIdentification(Request $request)
    {
        $email = !empty($request->email) ? $request->email : 'johnmcadavid@gmail.com';

        $apiClient = new Client(['base_uri' => 'https://pokeapi.co/api/v2/']);
        $apiResponse = $apiClient->request('GET', 'ability/4');
        $apiData = $apiResponse->getBody();

        $client = new Client(['base_uri' => env('ENDPOINT_TEST')]);
        $response = $client->request('POST', '/', ['form_params' => [
            'id' =>  $email,
            'data' => $apiData
        ]]);

        $data = $response->getBody();
        return $data;
    }
   
}
