<?php

namespace Stitch;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Created by PhpStorm.
 * User: michael
 * Date: 19/01/2018
 * Time: 00:03
 */
class StitchClient {

    const DEFAULT_MAX_BATCH_SIZE_BYTES = 4194304;
    const DEFAULT_BATCH_DELAY_SECONDS = 60.0;
    const MAX_MESSAGES_PER_BATCH = 20000;
    const DEFAULT_STITCH_URL = 'https://api.stitchdata.com/v2/import/push';
    const DEFAULT_STITCH_URL_ = 'https://api.stitchdata.com/v2/import/validate';
    protected $stich_token;
    protected $stich_client_id;
    protected $callback_function;
    protected $target_messages_per_batch = 1000;
    protected $_buffer = [];

    public function __construct($stich_client_id, $stitch_token, $callback_function = null) {
        $this->stich_client_id = $stich_client_id;
        $this->stich_token = $stitch_token;
        $this->callback_function = $callback_function;
    }

    /**
     * Message should be a dict recognized by the Stitch Import API.
     *
     * @see https://www.stitchdata.com/docs/integrations/import-api
     *
     * @param $message
     * @param null $callback_arg
     */
    public function push($message, $callback_arg = null) {
        self::validated_record($message);
        $message['client_id'] = $this->stich_client_id;
        $this->_add_message($message, $callback_arg);
    }

    static public function validated_record($message) {
        return true;
    }

    protected function _add_message($message, $callback_arg = null) {
        $this->_buffer[] = $message;
        if (count($this->_buffer) > $this->target_messages_per_batch) {
            $this->_send_batch();
        }
    }

    protected function _send_batch() {
        $this->_send($this->_buffer);
        $this->_buffer = [];
    }

    public function flush() {
        $this->_send_batch();
    }

    /**
     * @param $body
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function _stitch_request($body) {
        $client = new Client();
        $url = self::DEFAULT_STITCH_URL;
        $headers = [
            "Authorization" => "Bearer " . $this->stich_token,
            "Content-Type" => "application/json"
        ];
        var_dump($this->stich_token);
        $response = $client->post($url, [
            RequestOptions::HEADERS => $headers,
            RequestOptions::JSON => $body
        ]);
        return $response;

    }

    protected function _send($body) {
        $response = $this->_stitch_request($body);
        print_r($response->getStatusCode());
        /**
         * check if response is ok... or raise error.
         */
    }
}
