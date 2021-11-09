<?php

declare(strict_types=1);

namespace Tests\Unit\EraPays\Commission\Application\Service;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Application\DTO\TransactionsDtoCollection;
use EraPays\Infrastructure\Commission\Service\InputParser;
use Tests\TestCase;

class InputParserTest extends TestCase
{
    private InputParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = app(InputParser::class);
    }

    public function testParse(): void
    {
        $collection = $this->parser->parse('input_parser_test_input_correct.txt');

        $this->assertInstanceOf(TransactionsDtoCollection::class, $collection);
        $this->assertInstanceOf(TransactionDto::class, $collection->first());
    }
}
