<?php

declare(strict_types=1);

namespace App\Wallet\Application\Service;

use App\Wallet\Domain\Entity\Operation;
use App\Wallet\Domain\Entity\OperationType;
use App\Wallet\Domain\Entity\UserType;
use Money\Money;

class CalculateFeeService
{
    private CalculateFee $calculateFee;

    private DepositRule $depositRule;

    private WithdrawRuleForPrivate $withdrawRuleForPrivate;

    private WithdrawRuleForBusiness $withdrawRuleForBusiness;

    public function __construct(
        CalculateFee $calculateFee,
        DepositRule $depositRule,
        WithdrawRuleForPrivate $withdrawRuleForPrivate,
        WithdrawRuleForBusiness $withdrawRuleForBusiness,
    ) {
        $this->calculateFee = $calculateFee;
        $this->depositRule = $depositRule;
        $this->withdrawRuleForPrivate = $withdrawRuleForPrivate;
        $this->withdrawRuleForBusiness = $withdrawRuleForBusiness;
    }

    public function calculate(Operation $operation): Money
    {
        $operationTypeValue = $operation->getOperationType()->getValue();
        $userTypeValue = $operation->getUserType()->getValue();

        if ($operationTypeValue === OperationType::DEPOSIT_TYPE) {
            $this->calculateFee->setCalculateFeeRule($this->depositRule);
        } elseif ($operationTypeValue === OperationType::WITHDRAW_TYPE && $userTypeValue === UserType::PRIVATE_TYPE) {
            $this->calculateFee->setCalculateFeeRule($this->withdrawRuleForPrivate);
        } elseif ($operationTypeValue === OperationType::WITHDRAW_TYPE && $userTypeValue === UserType::BUSINESS_TYPE) {
            $this->calculateFee->setCalculateFeeRule($this->withdrawRuleForBusiness);
        }

        return $this->calculateFee->calculate($operation);
    }
}
