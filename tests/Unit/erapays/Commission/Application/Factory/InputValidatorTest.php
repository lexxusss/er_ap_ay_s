<?php

declare(strict_types=1);

namespace Tests\Unit\EraPays\Commission\Application\Factory;

use EraPays\Commission\Application\Factory\InputValidator;
use Tests\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class InputValidatorTest extends TestCase
{
    private InputValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = app(InputValidator::class);
    }

    /**
     * @dataProvider inputProvider
     */
    public function testValidateTransactionFormat(array $input, bool $success): void
    {
        if (false === $success) {
            $this->expectException(InvalidArgumentException::class);
        }

        $res = $this->validator->validateTransactionFormat($input);

        if (true === $success) {
            $this->assertEquals($success, $res);
        }
    }

    public function inputProvider(): array
    {
        return [
            [
                'input' =>  [
                    'bin' => 45717360,
                    'amount' => 100.00,
                    'currency' => 'EUR',
                ],
                'success' => true,
            ],
            [
                'input' =>  [
                    'bin' => 45717360,
                    'value' => 100.00,
                    'currency' => 'EUR',
                ],
                'success' => false,
            ],
        ];
    }
}
