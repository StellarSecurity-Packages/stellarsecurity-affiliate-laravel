<?php

namespace StellarSecurity\AffiliateLaravel;

use GuzzleHttp\Client;

class AffiliateClient
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected Client $http;

    public function __construct(string $baseUrl, string $username, string $password)
    {
        $this->baseUrl  = rtrim($baseUrl, '/');
        $this->username = $username;
        $this->password = $password;

        $this->http = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 5.0,
        ]);
    }

    public function orderPaid(array $payload): array
    {
        $body = array_merge(['event' => 'order.paid'], $payload);

        $response = $this->http->post('/affiliate/events/order-paid', [
            'auth' => [$this->username, $this->password],
            'json' => $body,
        ]);

        return json_decode((string) $response->getBody(), true) ?? [];
    }

    public function ping(): bool
    {
        try {
            $response = $this->http->get('/health', [
                'auth' => [$this->username, $this->password],
            ]);
            return $response->getStatusCode() === 200;
        } catch (\Throwable) {
            return false;
        }
    }
}
