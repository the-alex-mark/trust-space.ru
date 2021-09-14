<?php

namespace App\Helpers;

/**
 *
 */
class Api {

    /**
     * Возвращает список необходимых заголовков.
     *
     * @return array
     */
    public static function getHeaders() {
        return [
            'Content-type'                 => 'application/json',
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, X-Auth-Token , Authorization, X-Api-Token, x-api-token'
        ];
    }

}
