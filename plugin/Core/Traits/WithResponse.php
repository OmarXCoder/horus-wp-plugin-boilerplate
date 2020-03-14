<?php

namespace Horus\Core\Traits;

defined('ABSPATH') || exit('Forbidden!');

trait WithResponse
{

    public function respondSuccess($message = '', $date = [])
    {
        if (!$message) {
            $message = __('Done Successfully', 'horus');
        }

        wp_send_json([
            'success' => true,
            'message' => $message,
            'data' => $date
        ], 200);
    }

    public function respondCreated($date = [])
    {
        wp_send_json([
            'success' => true,
            'message' => __('Resource created successfully', 'horus'),
            'data' => $date
        ], 201);
    }

    public function respondBadrequest($errors = [])
    {
        wp_send_json([
            "success" => false,
            "message" => __('insuficient arguments provided.', 'horus'),
            "errors" => $errors
        ], 400);
    }

    public function respondForbidden($data = [])
    {
        wp_send_json([
            "success" => false,
            "message" => __('Forbidden', 'horus'),
            "data" => $data
        ], 403);
    }

    public function respondValidationFailed($errors = [], $message = null)
    {
        wp_send_json([
            "success" => false,
            "message" => $message ? $message : __('Unprocessable Entity', 'horus'),
            "errors" => $errors
        ], 422);
    }

    public function respondServerError()
    {
        wp_send_json([
            "success" => false,
            "message" => __('Sorry, something went wrong please try again later.', 'horus')
        ], 500);
    }
}
