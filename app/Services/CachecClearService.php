<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CachecClearService
{
    // call webhook to clear cache it program SISKOP/FMS
    // to make the roles/permission take effect. No
    // effect will be seen until cache is cleared.

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function clearCache($url)
    {
        try {
            $this->client->request('POST', $url);
            return true;
        } catch (GuzzleException $e) {
            \Log::error('******** ERROR CLEAR CACHE WEBHOOK LOG********');
            \Log::error($e);
            \Log::error('******** END ERROR CLEAR CACHE WEBHOOK LOG********');

            return false;
        }
    }
}