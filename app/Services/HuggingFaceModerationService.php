<?php

namespace App\Services;

use App\Contracts\CommentModerationInterface;
use Illuminate\Support\Facades\Http;
use Stichoza\GoogleTranslate\GoogleTranslate;



class HuggingFaceModerationService implements CommentModerationInterface
{
    protected string $hugginface_api_key;
    protected string $api_url;
    protected string $translation_api_url;


    public function __construct()
    {
        $this->api_url = 'https://api-inference.huggingface.co/models/unitary/toxic-bert';
        $this->hugginface_api_key = env('HUGGING_FACE_API_KEY');
    }

    public function isCommentToxic($comment): bool
    {
        $result = $this->analyzeComment($comment['message']);
        return !empty($result['reason']); // Se houver motivo, é tóxico
    }

    public function analyzeComment(string $message): array
    {
        /**traduz a mensagem para ingles
         * para maior acertividade
         */
        $message = $this->translateComment($message);

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->hugginface_api_key}"
        ])->post($this->api_url, [
            'inputs' =>  $message
        ]);

        if (!$response->successful()) {
            return ['toxic' => true, 'reason' => 'UNKNOWN'];
        }

        $data = $response->json();
        $reason = null;
        $best_category = ['score' => 0.3];

        /** faz um loop sobre o response da API para
         *  trazer qual o teor desse comentário que foi
         *  barrado(caso ele tenha sido)
         * 
         *  o metodo de verificação é se o score
         *  for maior que 0.3, sendo o score o 
         *  coeficiente de toxicidade
         */
        foreach ($data[0] as $category) {
            if($category['score'] > $best_category['score']) {
                $best_category = $category;
            }
        }

        if ($best_category['score'] == 0.3) {
            return ['toxic' => false, 'reason' => null];
        };


        /** como o nome da variavel que dita o teor do comentário
         *  toxico é diferente entre a /toxic-bert e GoTourBanhia
         *  faz um mapeamento de suas proximidades, e caso nem uma
         *  case, retorna o teor "OTHER"
         */
        $mapping = [
            'toxic' => 'HATE',
            'identity_hate' => 'HATE',
            'severe_toxic' => 'HATE',
            'insult' => 'BULLYING',
            'threat' => 'VIOLENCE',
            'obscene' => 'HATE'
            ];

        $reason = $mapping[$best_category['label']] ?? 'OTHER';;

        return ['toxic' => true, 'reason' => $reason];
    }

    protected function translateComment(string $message): string
    {
        try {
            return GoogleTranslate::trans($message, 'en', 'pt'); // Traduz do português para inglês
        } catch (\Exception $e) {
            return $message; // Se falhar, retorna o original
        }
    }
}