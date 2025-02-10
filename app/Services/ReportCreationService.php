<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ReportCreationService implements ReportCreationInterface
{
    private string $api_url;

    public function __construct()
    {
        $this->api_url = "https://go-tour-bahia.onrender.com/";
    }

    public function createReport(array $data): array
    {
        /** Faz um POST na api da  GoTourBanhia
         * com os dados de data para cadastrar
         * um novo report
        */
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->api_url . 'report', $data);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Erro ao criar denúncia');
    }
}
