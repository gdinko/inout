<?php

namespace Mchervenkov\Inout;

use Illuminate\Support\Facades\Http;
use Mchervenkov\Inout\Exceptions\InoutException;

trait MakesHttpRequests
{
    /**
     * Send Get Request
     *
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws InoutException
     */
    public function get(string $url, array $data = []): mixed
    {
        return $this->request('get', $url, $data);
    }

    /**
     * Send Post Request
     *
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws InoutException
     */
    public function post(string $url, array $data = []): mixed
    {
        return $this->request('post', $url, $data);
    }

    /**
     * Send Put Request
     *
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws InoutException
     */
    public function put(string $url, array $data = []): mixed
    {
        return $this->request('put', $url, $data);
    }

    /**
     * Send Api Request
     *
     * @param string $verb
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws InoutException
     */
    public function request(string $verb, string $url, array $data = []): mixed
    {
        $response = Http::withToken($this->apiToken)
            ->timeout($this->timeout)
            ->{$verb}("$this->baseUrl/$url", $data)
            ->throw(function ($response, $e) {
                throw new InoutException(
                    $e->getMessage(),
                    $e->getCode(),
                    $response->json()
                );
            });

        return $response->json();
    }
}
