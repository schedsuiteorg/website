<?php

if (!function_exists('curl_get')) {
    /**
     * Make a GET request using cURL
     *
     * @param string $url
     * @param array $headers
     * @return array
     */
    function curl_get($url, $headers = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }

        curl_close($ch);

        if (isset($error_msg)) {
            return ['error' => true, 'message' => $error_msg];
        }

        return json_decode($response, true);
    }
}

if (!function_exists('curl_post')) {
    /**
     * Make a POST request using cURL
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return string
     */
    function curl_post($url, $data = [], $headers = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }

        curl_close($ch);

        if (isset($error_msg)) {
            return json_encode(['error' => true, 'message' => $error_msg]);
        }

        return $response;
    }
}