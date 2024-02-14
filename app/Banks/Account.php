<?php

namespace Noorfarooqy\BankGateway\Banks;

/**
 * Class Account
 * @package Noorfarooqy\BankGateway\Banks
 */
class Account
{
    /**
     * @var int
     */
    private $account_number;

    /**
     * @var int
     */
    private $branch_code;

    /**
     * @var string
     */
    private $account_type;

    /**
     * @var string
     */
    private $account_status;

    /**
     * @var string
     */
    private $account_name;

    /**
     * @var float
     */
    private $account_balance;

    /**
     * @var bool
     */
    private $account_is_dormant;

    /**
     * @var bool
     */
    private $account_is_no_debit;

    /**
     * @var bool
     */
    private $account_is_no_credit;

    /**
     * @var bool
     */
    private $account_belongs_to_staff;

    /**
     * Account constructor.
     * @param int $account_number
     * @param int $branch_code
     * @param string $account_type
     * @param string $account_status
     * @param string $account_name
     * @param float $account_balance
     * @param bool $account_is_dormant
     * @param bool $account_is_no_debit
     * @param bool $account_is_no_credit
     * @param bool $account_belongs_to_staff
     */
    public function __construct(
        int $account_number,
        int $branch_code,
        string $account_type,
        string $account_status,
        string $account_name,
        float $account_balance,
        bool $account_is_dormant,
        bool $account_is_no_debit,
        bool $account_is_no_credit,
        bool $account_belongs_to_staff
    ) {
        $this->account_number = $account_number;
        $this->branch_code = $branch_code;
        $this->account_type = $account_type;
        $this->account_status = $account_status;
        $this->account_name = $account_name;
        $this->account_balance = $account_balance;
        $this->account_is_dormant = $account_is_dormant;
        $this->account_is_no_debit = $account_is_no_debit;
        $this->account_is_no_credit = $account_is_no_credit;
        $this->account_belongs_to_staff = $account_belongs_to_staff;
    }

    /**
     * @return int
     */
    public function getAccountNumber(): int
    {
        return $this->account_number;
    }

    /**
     * @param int $account_number
     */
    public function setAccountNumber(int $account_number): void
    {
        $this->account_number = $account_number;
    }

    /**
     * @return int
     */
    public function getBranchCode(): int
    {
        return $this->branch_code;
    }

    /**
     * @param int $branch_code
     */
    public function setBranchCode(int $branch_code): void
    {
        $this->branch_code = $branch_code;
    }

    /**
     * @return string
     */
    public function getAccountType(): string
    {
        return $this->account_type;
    }

    /**
     * @param string $account_type
     */
    public function setAccountType(string $account_type): void
    {
        $this->account_type = $account_type;
    }

    /**
     * @return string
     */
    public function getAccountStatus(): string
    {
        return $this->account_status;
    }

    /**
     * @param string $account_status
     */
    public function setAccountStatus(string $account_status): void
    {
        $this->account_status = $account_status;
    }

    /**
     * @return string
     */
    public function getAccountName(): string
    {
        return $this->account_name;
    }

    /**
     * @param string $account_name
     */
    public function setAccountName(string $account_name): void
    {
        $this->account_name = $account_name;
    }

    /**
     * @return float
     */
    public function getAccountBalance(): float
    {
        return $this->account_balance;
    }

    /**
     * @param float $account_balance
     */
    public function setAccountBalance(float $account_balance): void
    {
        $this->account_balance = $account_balance;
    }

    /**
     * @return bool
     */
    public function isAccountDormant(): bool
    {
        return $this->account_is_dormant;
    }

    /**
     * @param bool $account_is_dormant
     */
    public function setAccountDormant(bool $account_is_dormant): void
    {
        $this->account_is_dormant = $account_is_dormant;
    }

    /**
     * @return bool
     */
    public function isAccountNoDebit(): bool
    {
        return $this->account_is_no_debit;
    }

    /**
     * @param bool $account_is_no_debit
     */
    public function setAccountNoDebit(bool $account_is_no_debit): void
    {
        $this->account_is_no_debit = $account_is_no_debit;
    }

    /**
     * @return bool
     */
    public function isAccountNoCredit(): bool
    {
        return $this->account_is_no_credit;
    }

    /**
     * @param bool $account_is_no_credit
     */
    public function setAccountNoCredit(bool $account_is_no_credit): void
    {
        $this->account_is_no_credit = $account_is_no_credit;
    }

    /**
     * @return bool
     */
    public function isAccountBelongsToStaff(): bool
    {
        return $this->account_belongs_to_staff;
    }

    /**
     * @param bool $account_belongs_to_staff
     */
    public function setAccountBelongsToStaff(bool $account_belongs_to_staff): void
    {
        $this->account_belongs_to_staff = $account_belongs_to_staff;
    }
}
