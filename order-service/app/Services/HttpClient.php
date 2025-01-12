<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\HttpClientFactoryInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use stdClass;
use Webmozart\Assert\Assert;

/**
 * @final
 */
class HttpClient implements HttpClientFactoryInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {

    }

    /**
     * @return stdClass
     */
    public function getRequest(string $url): stdClass
    {
        // @todo move to ENV
        $client = new Client([
            'base_uri' => 'http://nginx:8081',
        ]);

        try {
            $response = $client
                ->get($url)
                ->getBody()
                ->getContents();

            $data = json_decode($response);

            Assert::isInstanceOf($data, stdClass::class);

            $this
                ->logger
                ->info('Response', [
                    'data' => $data,
            ]);

            return $data;
        } catch (ClientException $exception) {
            $this->logger->error($exception->getMessage());

            throw new Exception($exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->logger->error($exception->getMessage());

            throw new Exception($exception->getMessage());
        }
    }
}
