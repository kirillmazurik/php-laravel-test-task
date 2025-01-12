<?php

declare(strict_types=1);

namespace App\Contracts;

use stdClass;

interface HttpClientFactoryInterface
{
    /**
     * @return stdClass
     */
    public function getRequest(string $url): stdClass;
}
