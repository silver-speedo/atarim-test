<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UrlShortenerContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as Validated;
use Throwable;

class UrlShortenerController extends Controller
{
    public function encode(Request $request, UrlShortenerContract $service): JsonResponse
    {
        try {
            $validateRequest = $this->validateRequest($request);

            if($validateRequest->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => $service->encode($request->get('url')),
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function decode(Request $request, UrlShortenerContract $service): JsonResponse
    {
        try {
            $validateRequest = $this->validateRequest($request);

            if($validateRequest->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateRequest->errors()
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => $service->decode($request->get('url')),
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    private function validateRequest(Request $request): Validated
    {
        return Validator::make($request->all(),
            [
                'url' => 'required|url',
            ]);
    }
}
