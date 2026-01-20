<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Encrypt;
use Doctrine\ODM\MongoDB\Mapping\Annotations\EncryptQuery;

/**
 * Represents a financial account document.
 */
#[ODM\Document(collection: 'accounts')]
class Account
{
    /**
     * Unique identifier for the account.
     *
     * @var string|null
     */
    #[ODM\Id]
    private ?string $id = null;

    /**
     * The name of the customer.
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    private string $customerName;

    /**
     * The account number (Encrypted).
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    #[Encrypt(queryType: EncryptQuery::Equality)]
    private string $accountNumber;

    /**
     * The current balance of the account (Encrypted).
     *
     * @var float
     */
    #[ODM\Field(type: 'float')]
    #[Encrypt(queryType: EncryptQuery::Range, min: 0.0, max: 10000000.0, precision: 2)]
    private float $balance;

    /**
     * The customer's Social Security Number (Encrypted).
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    #[Encrypt(queryType: EncryptQuery::Equality)]
    private string $ssn;

    /**
     * The customer's email address.
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    private string $email;

    /**
     * The date and time when the account was created.
     *
     * @var \DateTime
     */
    #[ODM\Field(type: 'date')]
    private \DateTime $createdAt;

    /**
     * Constructor.
     * Initializes the creation date.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // Getters
    /**
     * Get the account ID.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
    /**
     * Get the customer name.
     *
     * @return string
     */
    public function getCustomerName(): string
    {
        return $this->customerName;
    }
    /**
     * Get the account number.
     *
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }
    /**
     * Get the account balance.
     *
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
    /**
     * Get the SSN.
     *
     * @return string
     */
    public function getSsn(): string
    {
        return $this->ssn;
    }
    /**
     * Get the email address.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    /**
     * Get the creation date.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    // Setters
    /**
     * Set the customer name.
     *
     * @param string $customerName
     * @return self
     */
    public function setCustomerName(string $customerName): self
    {
        $this->customerName = $customerName;
        return $this;
    }
    /**
     * Set the account number.
     *
     * @param string $accountNumber
     * @return self
     */
    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }
    /**
     * Set the account balance.
     *
     * @param float $balance
     * @return self
     */
    public function setBalance(float $balance): self
    {
        $this->balance = $balance;
        return $this;
    }
    /**
     * Set the SSN.
     *
     * @param string $ssn
     * @return self
     */
    public function setSsn(string $ssn): self
    {
        $this->ssn = $ssn;
        return $this;
    }
    /**
     * Set the email address.
     *
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    /**
     * Set the creation date.
     *
     * @param \DateTime $createdAt
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}