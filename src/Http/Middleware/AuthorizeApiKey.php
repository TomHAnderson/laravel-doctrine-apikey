<?php

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Http\Middleware;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Illuminate\Http\Request;

class AuthorizeApiKey
{
    private ApiKeyService $apiKeyService;

    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
    }

    /**
     * Handle request
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, string $scope = null)
    {
        $header = $request->header('Authorization');
        // Remove Bearer from key prefix
        $key = substr($header, 7);

        $apiKey = $this->apiKeyService->isActive($key);
        if ($apiKey) {
            if ($scope) {
                // If a scope is passed then verify it exists for the key
                if ($this->apiKeyService->hasScope($key, $scope)) {
                    $this->apiKeyService->logAccessEvent($request, $apiKey);

                    return $next($request);
                }
            } else {
                $this->apiKeyService->logAccessEvent($request, $apiKey);

                return $next($request);
            }
        }

        return response([
            'errors' => [[
                'message' => 'Unauthorized'
            ]]
        ], 401);
    }
}

