<?php

namespace App\Service;

class TaskProviderFactory
{
    private iterable $providers;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function getProvider(string $providerName): ?TaskProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider instanceof $providerName) {
                return $provider;
            }
        }
        return null;
    }
}
