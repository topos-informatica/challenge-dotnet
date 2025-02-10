<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExternalApiService;
// use Illuminate\Support\Facades\Http;


class ExternalApiController extends Controller
{
    protected $external_api_service;

    public function __construct(ExternalApiService $external_api_service)
    {
        $this->external_api_service = $external_api_service;
    }


    /** Retorna a lista de de lugares e seus comentarios */
    public function getPlacesWithComments()
    {
        try {
            $places = $this->external_api_service->getPlacesWithComments();
            return response()->json($places);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /** Cria uma denúncia de um comentário */
    public function createReport(Request $request)
    {
        $data = $request->all();

        try {
            $report = $this->external_api_service->createReport($data);
            return response()->json($report);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}