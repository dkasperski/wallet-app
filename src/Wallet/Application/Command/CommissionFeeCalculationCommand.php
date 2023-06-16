<?php

declare(strict_types=1);

namespace App\Wallet\Application\Command;

use App\Wallet\Application\FeeDataSource\CSVSource;
use App\Wallet\Application\FeeDataSource\FeeDataSource;
use App\Wallet\Application\Service\CreateOperationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:calculate-fee',
    description: 'Commission fee calculation.',
    hidden: false
)]
final class CommissionFeeCalculationCommand extends Command
{
    private CreateOperationService $createOperationService;

    private FeeDataSource $feeDataSource;

    private CSVSource $CSVSource;

    public function __construct(
        CreateOperationService $createOperationService,
        FeeDataSource $feeDataSource,
        CSVSource $CSVSource,
    ) {
        $this->createOperationService = $createOperationService;
        $this->feeDataSource = $feeDataSource;
        $this->CSVSource = $CSVSource;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to calculate fee')
                ->addArgument(
                    'data-source',
                    InputArgument::OPTIONAL,
                    'Source of data for fee calculation',
                    FeeDataSource::DEFAULT
                );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Fee Calculation',
            '============',
            '',
        ]);

        $output->writeln('You are about to calculate fees');

        $dataSource = $input->getArgument('data-source');
        if ($dataSource === 'CSVSource') {
            $this->feeDataSource->setFeeDataSource($this->CSVSource);
        }

        foreach ($this->feeDataSource->read() as $data) {
            $operation = $this->createOperationService->handle($data);
            $feeOperationAmount = $operation->getFeeOperation() ? (string) ($operation->getFeeOperation()->getValue()->getAmount() / 100) : '';
            $output->writeln($feeOperationAmount);
        }

        $output->writeln([
            'Fee calculated : ',
            '',
        ]);

        return Command::SUCCESS;
    }
}
