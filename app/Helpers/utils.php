<?php

use App\Mail\GeneralMail;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;


if (!function_exists('generateReference')) {
    function generateReference()
    {
        $reference = "EB- " . Str::random(10);

        return $reference;
    }
}

// app/helpers.php

if (!function_exists('successResponse')) {
    /**
     * Returns a success response with optional data.
     *
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function successResponse($msg, $data = null, $statusCode = 200)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $msg
        ];

        return response()->json($response, $statusCode);
    }
}

if (!function_exists('failureResponse')) {
    /**
     * Returns a failure response with optional message and status code.
     *
     * @param string|null $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function failureResponse($message = null, $error = null, $statusCode = 400)
    {
        $response = [
            'success' => false,
            'message' => $message ?: 'Failed.',
            'error' => $error
        ];

        return response()->json($response, $statusCode);
    }
}

if (!function_exists(('sendMail'))) {
    function sendMail($email, $subject, $view, $data)
    {
        try {
            Mail::to($email)->send(new GeneralMail($subject, $view, $data));
        } catch (\Exception $e) {
            logger($e->getMessage(), [
                "error" => $e->getMessage(),
            ]);
        }
    }
}
if (!function_exists(('sendMail'))) {
    function uploadFile(UploadedFile $file, $path)
    {
        $filename = rand(00000, 99999) . '.' . $file->getClientOriginalExtension();
        $file->storeAs("uploads/" . $path, $filename);
        return $filename;
    }
}
if (!function_exists(('sendMail'))) {
    function generateCode()
    {
        $code = mt_rand(105000, 999999);

        return [$code, now()->addMinutes(30)];
    }
}
