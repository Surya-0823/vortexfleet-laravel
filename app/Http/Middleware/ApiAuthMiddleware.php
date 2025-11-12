<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Models\ApiToken; // We will migrate this model later
use Closure; // Import the Closure type-hint

/**
 * Handles authentication for all API requests.
 * It checks for a valid 'X-AUTH-TOKEN' in the header.
 */
class ApiAuthMiddleware
{
    /**
     * Store the authenticated user model (Driver or Student).
     */
    protected $authenticatedUser = null;
    
    /**
     * Store the role of the authenticated user ('driver' or 'student').
     */
    protected $userRole = null;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Check if the API auth is valid
        $authErrorResponse = $this->checkApiAuth($request);

        // 2. If auth fails, return the error response immediately
        if ($authErrorResponse) {
            return $authErrorResponse;
        }

        // 3. If auth succeeds, add user data to the request
        //    so the controller (AppApiController) can access it.
        $request->attributes->add(['authenticatedUser' => $this->authenticatedUser]);
        $request->attributes->add(['userRole' => $this->userRole]);

        // 4. Send the request to the next step (the controller)
        return $next($request);
    }

    /**
     * Check for a valid 'X-AUTH-TOKEN' in the request header.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|null
     */
    private function checkApiAuth(Request $request)
    {
        // Note: The old 'Container' and 'Controller' logic is removed.
        // We return Laravel JsonResponses directly.

        // 1. Get the token from the request header
        $token = $request->header('X-AUTH-TOKEN');

        if (!$token) {
            // Use Laravel's response() helper
            return response()->json(['success' => false, 'message' => 'Unauthorized: Auth Token missing'], 401);
        }

        // 2. Find the token in the 'api_tokens' table
        $apiToken = ApiToken::where('token', $token)->first();
        
        if (!$apiToken) {
            return response()->json(['success' => false, 'message' => 'Unauthorized: Invalid Auth Token'], 401);
        }

        // 3. Check if the token has expired
        if (strtotime($apiToken->expires_at) < time()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized: Token expired. Please login again.'], 401);
        }

        // 4. Get the user (Driver or Student) via the polymorphic relationship
        $user = $apiToken->tokenable; // This is the magic!

        if (!$user) {
            // Token exists, but the user it belongs to was deleted
            return response()->json(['success' => false, 'message' => 'Unauthorized: User not found for this token.'], 401);
        }

        // 5. Store the user and role for the 'handle' method
        $this->authenticatedUser = $user;
        // Get role from class name (e.g., 'App\Models\Driver' -> 'driver')
        $this->userRole = strtolower(class_basename($user)); 
        
        return null; // Auth is successful!
    }
}