<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Provider;

use EraPays\Commission\Application\Exception\InvalidResponseException;
use EraPays\Commission\Application\Service\BinProviderInterface;
use GuzzleHttp\Client;
use JsonException;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class BinProvider implements BinProviderInterface
{
    private const RESPONSE_COUNTRY_KEY = 'country';
    private const RESPONSE_COUNTRY_ALPHA2_KEY = 'alpha2';

    private Client $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getCountryCode(int $bin): string
    {
        $response = $this->client->get(sprintf('%s', $bin));

        try {
            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            Assert::keyExists($data, self::RESPONSE_COUNTRY_KEY);
            Assert::keyExists($data[self::RESPONSE_COUNTRY_KEY], self::RESPONSE_COUNTRY_ALPHA2_KEY);
        } catch (JsonException|InvalidArgumentException $e) {
            throw new InvalidResponseException($e->getMessage());
        }

        return $data[self::RESPONSE_COUNTRY_KEY][self::RESPONSE_COUNTRY_ALPHA2_KEY];
    }
}
