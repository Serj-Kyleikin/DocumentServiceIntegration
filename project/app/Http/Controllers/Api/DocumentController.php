<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\{
    Document\DocumentService,
};
use Illuminate\{
    Http\JsonResponse,
    Support\Facades\Log,
};
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentService $documentService,
    )
    {
    }

    public function store(): JsonResponse
    {
        try {

            $fileName = $this->documentService->createDocument('Claim');
    
            return response()->json(['status' => true, 'document_url' => $fileName], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            env('A_MODE') && Log::critical($e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}