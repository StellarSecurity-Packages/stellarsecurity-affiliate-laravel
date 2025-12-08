<?php

namespace StellarSecurity\AffiliateLaravel;

use Illuminate\Support\ServiceProvider;

class AffiliateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stellar_affiliate.php', 'stellar_affiliate');

        $this->app->singleton(AffiliateClient::class, function ($app) {
            $config = $app['config']->get('stellar_affiliate', []);
            return new AffiliateClient(
                $config['base_url'] ?? '',
                $config['username'] ?? '',
                $config['password'] ?? ''
            );
        });

        $this->app->alias(AffiliateClient::class, 'stellar.affiliate');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/stellar_affiliate.php' => config_path('stellar_affiliate.php'),
        ], 'stellar-affiliate-config');
    }
}
