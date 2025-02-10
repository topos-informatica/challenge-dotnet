<?php 

namespace App\Services;

use App\Contracts\CommentModerationInterface;
use App\Services\ReportCreationService;
use Illuminate\Support\Facades\Http;

class ExternalApiService
{
    private string $api_url;
    private CommentModerationInterface $moderation_service;
    private ReportCreationService $report_creation_service;

    // Passando as dependências pelo construtor
    public function __construct(CommentModerationInterface $moderation_service, ReportCreationService $report_creation_service)
    {
        // url base da API
        $this->api_url = env('EXTERNAL_API_URL');
        
        // Inicializando as dependências
        $this->report_creation_service = $report_creation_service;
        $this->moderation_service = $moderation_service;
    }

    /** Retorna a lista de de lugares e seus comentarios */
    public function getPlacesWithComments()
    {
        /** Acessa a API GoTourBanhia com a 
         * requisição que retorna os lugares
         * e seus respectivos comentarios
         */
        $response = Http::get($this->api_url . 'place');

        if ($response->successful()) {
            $places = $response->json();

            /** Faz um loop no obj de lugares para assim fazer um filter
             *  no array com os comentários, para que seja chamado o service
             *  de analise de toxicidade e retirar os comentários indesejados
             */
            foreach ($places as &$place) {
                if (isset($place['Comments']) && is_array($place['Comments'])) {
                    $place['Comments'] = array_values(array_filter($place['Comments'], function ($comment) {
                        $analysis = $this->moderation_service->analyzeComment($comment['message']);

                        /** em caso de comentário toxico, alem de filtra-lo do array
                         * também aciona o serviço de report
                         */
                        if ($analysis['toxic']) {
                            $this->createReport([
                                'id' => $comment['id'],
                                'place_id' => $comment['place_id'],
                                'message' => $comment['message'],
                                'reason' => $analysis['reason']
                            ]);
                            return false; // Remove comentário tóxico
                        }
    
                        return true; // Mantém comentário normal
                    }));
                }
            }
        }

        if (!$response->successful() || !isset($places)) {
            throw new \Exception('Erro ao obter lugares e comentários');
        }

        return $places;
    }

    /** Cria uma denúncia de um comentário
     *  baseado na no parametro $data, que deve conter:
     *  "commentId": "string"
     *  "placeId": "string"
     *  "message": "string"
     *  "reason": "SPAM"(ex)
     */ 
    public function createReport(array $data)
    {
        return $this->report_creation_service->createReport($data);
    }
}
