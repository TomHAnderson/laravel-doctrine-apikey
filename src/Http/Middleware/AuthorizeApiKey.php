<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\Doctrine\ApiKey\Http\Middleware;

use ApiSkeletons\Laravel\Doctrine\ApiKey\Service\ApiKeyService;
use Closure;
use Illuminate\Http\Request;

use function response;
use function substr;

class AuthorizeApiKey
{
    private ApiKeyService $apiKeyService;

    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
    }

    /**
     * Handle request
     */
    public function handle(Request $request, Closure $next, ?string $scope = null): mixed
    {
        $header = $request->header('Authorization');
        // Remove Bearer from key prefix
        if (! $header) {
            return response([
                'errors' => [
                    ['message' => 'Unauthorized'],
                ],
            ], 401);
        }

        $key = substr($header, 7);

        $apiKey = $this->apiKeyService->isActive($key);
        if ($apiKey) {
            if (! $scope) {
                $this->apiKeyService->logAccessEvent($request, $apiKey);
                $request->attributes->set('apikey', $apiKey);

                return $next($request);
            }

            // If a scope is passed then verify it exists for the key
            if ($this->apiKeyService->hasScope($key, $scope)) {
                $this->apiKeyService->logAccessEvent($request, $apiKey);
                $request->attributes->set('apikey', $apiKey);

                return $next($request);
            }
        }

        return response([
            'errors' => [
                ['message' => 'Unauthorized'],
            ],
        ], 401);
    }
}
