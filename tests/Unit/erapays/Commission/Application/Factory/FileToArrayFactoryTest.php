<?php

declare(strict_types=1);

namespace Tests\Unit\EraPays\Commission\Application\Factory;

use EraPays\Commission\Application\Exception\FileParsingException;
use EraPays\Commission\Application\Factory\FileToArrayFactory;
use Tests\TestCase;

class FileToArrayFactoryTest extends TestCase
{
    private FileToArrayFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = app(FileToArrayFactory::class);
    }

    /**
     * @dataProvider inputProvider
     */
    public function testMake(string $filename, bool $success, ?array $result): void
    {
        if (false === $success) {
            $this->expectException(FileParsingException::class);
        }

        $res = $this->factory->convert($filename);

        if (true === $success) {
            $this->assertEquals($result, $res);
        }
    }

    public function inputProvider(): array
    {
        return [
            [
                'filename' =>  'file_to_array_test_input_correct.txt',
                'success' => true,
                'result' =>  [
                    [
                        'bin' => 45717360,
                        'amount' => 100.00,
                        'currency' => 'EUR',
                    ],
                ],
            ],
            [
                'filename' =>  'file_to_array_test_input_wrong_format.txt',
                'success' => false,
                'result' => null,
            ],
            [
                'filename' =>  'file_to_array_test_input_wrong_keys.txt',
                'success' => false,
                'result' => null,
            ],
        ];
    }
}
