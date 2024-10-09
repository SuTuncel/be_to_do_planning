<?php

namespace App\Service;

interface TaskProviderInterface
{
    public function getTasks(): array;
}