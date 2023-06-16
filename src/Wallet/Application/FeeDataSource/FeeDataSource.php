<?php

declare(strict_types=1);

namespace App\Wallet\Application\FeeDataSource;

class FeeDataSource
{
    public const DEFAULT = 'CSVSource';

    private FeeDataSourceInterface $feeDataSource;

    public function __construct(FeeDataSourceInterface $feeDataSource)
    {
        $this->feeDataSource = $feeDataSource;
    }

    public function setFeeDataSource(FeeDataSourceInterface $feeDataSource)
    {
        $this->feeDataSource = $feeDataSource;
    }

    public function read()
    {
        return $this->feeDataSource->read();
    }
}
