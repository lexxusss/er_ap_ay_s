<?php

declare(strict_types=1);

namespace EraPays\Infrastructure\Commission\Service\Provider;

use EraPays\Commission\Application\Exception\InvalidResponseException;
use EraPays\Commission\Application\Service\RateProviderInterface;
use EraPays\Commission\Domain\Enum\Currency;
use GuzzleHttp\Client;
use JsonException;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class RateProvider implements RateProviderInterface
{
    private const QUERY_ACCESS_KEY = 'access_key';
    private const QUERY_CURRENCY_KEY = 'symbols';
    private const RESPONSE_RATES_KEY = 'rates';

    private Client $client;
    private string $key;

    public function __construct($client, string $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    public function getRate(Currency $currency): ?float
    {
        $response = $this->client->get(sprintf(
            '?%s=%s&%s=%s',
            self::QUERY_ACCESS_KEY,
            $this->key,
            self::QUERY_CURRENCY_KEY,
            $currency->value()
        ));

        try {
            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            Assert::keyExists($data, self::RESPONSE_RATES_KEY);
            Assert::keyExists($data[self::RESPONSE_RATES_KEY], $currency->value());
        } catch (JsonException|InvalidArgumentException $e) {
            throw new InvalidResponseException($e->getMessage());
        }

        return $data[self::RESPONSE_RATES_KEY][$currency->value()];
    }
}
