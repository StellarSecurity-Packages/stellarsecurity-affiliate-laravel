<?php

namespace StellarSecurity\AffiliateLaravel;

use GuzzleHttp\Client;
class AffiliateClient
{
    /**
     * Base URL for the affiliate API, for example:
     *
     * Do NOT include a trailing slash when passing it in.
     */
    protected string $baseUrl;

    /**
     * Basic auth username for the affiliate API.
     */
    protected string $username;

    /**
     * Basic auth password for the affiliate API.
     */
    protected string $password;

    /**
     * Underlying HTTP client.
     */
    protected Client $http;

    /**
     * @param string $baseUrl  Base URL without trailing slash
     * @param string $username Basic auth username
     * @param string $password Basic auth password
     */
    public function __construct(string $baseUrl, string $username, string $password)
    {
        // Normalize base URL so we control the trailing slash
        $this->baseUrl  = rtrim($baseUrl, '/');
        $this->username = $username;
        $this->password = $password;

        $this->http = new Client([
            'base_uri' => $this->baseUrl . '/', // e.g. https://stellarafi.com/api/v1/
            'timeout'  => 5.0,
        ]);
    }

    /**
     * Notify the affiliate service that an order has been paid.
     *
     * Expected payload keys (examples):
     *  - event            => 'order.paid'
     *  - order_id         => int
     *  - user_id          => int|null
     *  - external_user_id => int|null
     *  - affiliate_id     => int|null
     *  - affiliate_code   => string|null
     *  - product          => string
     *  - plan             => string
     *  - amount           => float
     *  - currency         => 'EUR', 'USD', etc.
     *  - is_initial       => bool
     *  - subscription_id  => int|null
     *
     * Throws on HTTP errors; caller should catch \Throwable if needed.
     */
    public function orderPaid(array $payload): array
    {
        // Ensure event name is always set
        $body = array_merge(['event' => 'order.paid'], $payload);

        $response = $this->http->post('affiliate/events/order-paid', [
            'auth' => [$this->username, $this->password],
            'json' => $body,
        ]);

        return json_decode((string) $response->getBody(), true) ?? [];
    }

    /**
     * Simple health check against the affiliate API.
     *
     * Returns true if the /health endpoint responds with HTTP 200.
     */
    public function ping(): bool
    {
        try {
            $response = $this->http->get('health', [
                'auth' => [$this->username, $this->password],
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Throwable) {
            return false;
        }
    }
}
