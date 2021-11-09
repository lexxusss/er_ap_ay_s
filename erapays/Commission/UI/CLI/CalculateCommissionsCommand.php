<?php

declare(strict_types=1);

namespace EraPays\Commission\UI\CLI;

use EraPays\Commission\Application\DTO\TransactionDto;
use EraPays\Commission\Application\DTO\TransactionsDtoCollection;
use EraPays\Commission\Application\Service\CommissionCalculatorInterface;
use EraPays\Commission\Application\Service\InputParserInterface;
use Illuminate\Console\Command;

class CalculateCommissionsCommand extends Command
{
    protected $signature = 'comissions:caclulate {filename} {--dry}';
    protected $description = 'Calculate commissions for already made transactions.';

    public function handle(
        InputParserInterface $parser,
        CommissionCalculatorInterface $commissionCalculator
    ): void {
        $transactions = $parser->parse(
            $this->argument('filename')
        );

        $commissionCalculator->calculate($transactions);

        if ($this->option('dry')) {
            $this->printCommissions($transactions);
        } else {
            // TODO: some commissions business logic here...
        }
    }

    private function printCommissions(TransactionsDtoCollection $transactions): void
    {
        $transactions->each(function (TransactionDto $transaction): void {
            if ($transaction->getCommissionFailedReason()) {
                $this->info($transaction->getCommissionFailedReason());
            } else {
                $this->info($transaction->getAmountCommissioned());
            }
        });
    }
}
