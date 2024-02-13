<?php

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

if (! function_exists('isJSON') ) {
    /**
     * Check if a string is json or not
     *
     * @param string
     * @return boolean
     */
    function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true));
    }
}

if (! function_exists('getJSONMessage') ) {
    /**
     *
     * @param $message : string
     * @return Mixed (string or json string)
     */
    function getJSONMessage($message)
    {
        $msg = json_decode($message, true);
        if ($msg['message'] ?? false) {
            $message = $msg['message'];
        }

        return $message;
    }
}

if (! function_exists('getJSONErrors') ) {
    /**
     *
     * @param $message : string
     * @return Mixed (string or json string)
     */
    function getJSONErrors($message)
    {
        $msg = json_decode($message, true);
        if ($msg['errors'] ?? false) {
            $message = $msg['errors'];
        }

        return $message;
    }
}

if (! function_exists('successJsonResponse')) {

    /**
     * Format "Success" response in json
     *
     * @param array $response
     * @return JsonResponse
     */
    function successJsonResponse(array $response): JsonResponse 
    {
        $message = $response['message'] ?? '';

        $response = [
            'code_key' => 'SUCCESS',
            'error' => false,
            'success' => true,
            'message' => $message,
            'data' => [...$response],
        ];

        $responseStatusCode = $response['status_code'] ?? Response::HTTP_OK;

        return response()->json($response, $responseStatusCode);
    }
}

if (! function_exists('errorJsonResponse')) {

    /**
     * Format "Error" response in json
     *
     * @param array $response
     * @return JsonResponse
     */
    function errorJsonResponse(array $response): JsonResponse 
    {
        $message = $response['message'] ?? '';

        $response = [
            'code_key' => 'FAILURE',
            'error' => true,
            'success' => false,
            'message' => $message,
            'errors' => [...$response],
        ];

        $responseStatusCode = $response['status_code'] ?? Response::HTTP_INTERNAL_SERVER_ERROR;

        return response()->json($response, $responseStatusCode);
    }
}

if (! function_exists('errorExceptionJsonResponse')) {

    /**
     * Format "Error Exception" response in json.
     *
     * @param \Exception $e
     * @return JsonResponse
     */
    function errorExceptionJsonResponse(\Exception $e): JsonResponse
    {
        $statusCode = $e->getCode();
        $statusCode = ($statusCode >= Response::HTTP_OK && $statusCode <= Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED) 
                    ? $statusCode 
                    : Response::HTTP_INTERNAL_SERVER_ERROR;

        $message = $e->getMessage();

        $errors = (isJSON($message)) ? getJSONErrors($message) : '';
        $message = (isJSON($message)) ? getJSONMessage($message) : $message;

        $errorResponse = [
            'code_key' => 'FAILURE',
            'error' => true,
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        // only in case of local env and debug mode on
        if (config('app.debug')) {
            $errorResponse['code'] = $statusCode;
            $errorResponse['file'] = $e->getFile();
            $errorResponse['line'] = $e->getLine();
        }

        return response()->json($errorResponse, $statusCode);
    }
}