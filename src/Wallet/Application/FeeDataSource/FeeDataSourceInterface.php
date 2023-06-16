<?php

declare(strict_types=1);

namespace App\Wallet\Application\FeeDataSource;

interface FeeDataSourceInterface
{
    public function read();
}
