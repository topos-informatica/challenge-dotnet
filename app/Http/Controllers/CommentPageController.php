<?php 

namespace App\Http\Controllers;

use App\Services\ExternalApiService;
use Illuminate\Http\Request;

class CommentPageController extends Controller
{
    protected $external_api_service;

    public function __construct(ExternalApiService $external_api_service)
    {
        $this->external_api_service = $external_api_service;
    }

    /** Exibe a página com os comentários moderados */
    public function index()
    {
        try {
            $places = $this->external_api_service->getPlacesWithComments();
            return view('comments-page', compact('places'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
