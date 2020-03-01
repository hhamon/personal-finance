<?php

namespace App\Dto\Transaction;

use App\Entity\Account\Account;
use App\Entity\TaxPayer\TaxPayer;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

abstract class TransactionData {
    /**
     * @Assert\Length(max=128)
     */
    private ?string $title = null;

    /**
     * @Assert\NotNull()
     */
    private ?float $total;


    /**
     * @Assert\NotNull()
     */
    private ?DateTime $time;

    /**
     * @Assert\Length(max=65535)
     */
    private ?string $description;

    /**
     * @Assert\NotNull()
     */
    private ?Account $account;

    /**
     * @Assert\NotNull()
     */
    private ?TaxPayer $taxPayer;

    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float {
        return $this->total;
    }

    /**
     * @param float|null $total
     */
    public function setTotal(?float $total): void {
        $this->total = $total;
    }

    /**
     * @return DateTime|null
     */
    public function getTime(): ?DateTime {
        return $this->time;
    }

    /**
     * @param DateTime|null $time
     */
    public function setTime(?DateTime $time): void {
        $this->time = $time;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account {
        return $this->account;
    }

    /**
     * @param Account|null $account
     */
    public function setAccount(?Account $account): void {
        $this->account = $account;
    }

    /**
     * @return TaxPayer|null
     */
    public function getTaxPayer(): ?TaxPayer {
        return $this->taxPayer;
    }

    /**
     * @param TaxPayer|null $taxPayer
     */
    public function setTaxPayer(?TaxPayer $taxPayer): void {
        $this->taxPayer = $taxPayer;
    }

}
