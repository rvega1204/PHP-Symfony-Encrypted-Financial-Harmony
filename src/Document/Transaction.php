<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Encrypt;
use Doctrine\ODM\MongoDB\Mapping\Annotations\EncryptQuery;

/**
 * Represents a financial transaction.
 */
#[ODM\Document(collection: 'transactions')]
class Transaction
{
    /**
     * Unique identifier for the transaction.
     *
     * @var string|null
     */
    #[ODM\Id]
    private ?string $id = null;

    /**
     * The account number associated with the transaction (Encrypted).
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    #[Encrypt(queryType: EncryptQuery::Equality)]
    private string $accountNumber;

    /**
     * The amount of the transaction (Encrypted).
     *
     * @var float
     */
    #[ODM\Field(type: 'float')]
    #[Encrypt(queryType: EncryptQuery::Range, min: 0.0, max: 1000000.0, precision: 2)]
    private float $amount;

    /**
     * The type of transaction (e.g., 'deposit', 'withdrawal', 'transfer').
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    private string $transactionType; // 'deposit', 'withdrawal', 'transfer'

    /**
     * A description of the transaction.
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    private string $description;

    /**
     * The card number involved (Encrypted).
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    #[Encrypt]
    private string $cardNumber;

    /**
     * The card CVV (Encrypted).
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    #[Encrypt]
    private string $cvv;

    /**
     * The card expiry date (Encrypted).
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    #[Encrypt]
    private string $expiryDate;

    /**
     * The merchant name associated with the transaction.
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    private string $merchantName;



    /**
     * The date and time of the transaction.
     *
     * @var \DateTime
     */
    #[ODM\Field(type: 'date')]
    private \DateTime $transactionDate;

    /**
     * The status of the transaction (e.g., 'pending', 'completed', 'failed').
     *
     * @var string
     */
    #[ODM\Field(type: 'string')]
    private string $status; // 'pending', 'completed', 'failed'

    /**
     * Constructor.
     * Initializes transaction date and sets status to pending.
     */
    public function __construct()
    {
        $this->transactionDate = new \DateTime();
        $this->status = 'pending';
    }

    // Getters
    /**
     * Get the transaction ID.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
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
     * Get the transaction amount.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
    /**
     * Get the transaction type.
     *
     * @return string
     */
    public function getTransactionType(): string
    {
        return $this->transactionType;
    }
    /**
     * Get the transaction description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    /**
     * Get the card number.
     *
     * @return string
     */
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }
    /**
     * Get the CVV.
     *
     * @return string
     */
    public function getCvv(): string
    {
        return $this->cvv;
    }
    /**
     * Get the expiry date.
     *
     * @return string
     */
    public function getExpiryDate(): string
    {
        return $this->expiryDate;
    }
    /**
     * Get the merchant name.
     *
     * @return string
     */
    public function getMerchantName(): string
    {
        return $this->merchantName;
    }
    /**
     * Get the transaction date.
     *
     * @return \DateTime
     */
    public function getTransactionDate(): \DateTime
    {
        return $this->transactionDate;
    }
    /**
     * Get the transaction status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    // Setters
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
     * Set the transaction amount.
     *
     * @param float $amount
     * @return self
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }
    /**
     * Set the transaction type.
     *
     * @param string $transactionType
     * @return self
     */
    public function setTransactionType(string $transactionType): self
    {
        $this->transactionType = $transactionType;
        return $this;
    }
    /**
     * Set the transaction description.
     *
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    /**
     * Set the card number.
     *
     * @param string $cardNumber
     * @return self
     */
    public function setCardNumber(string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }
    /**
     * Set the CVV.
     *
     * @param string $cvv
     * @return self
     */
    public function setCvv(string $cvv): self
    {
        $this->cvv = $cvv;
        return $this;
    }
    /**
     * Set the expiry date.
     *
     * @param string $expiryDate
     * @return self
     */
    public function setExpiryDate(string $expiryDate): self
    {
        $this->expiryDate = $expiryDate;
        return $this;
    }
    /**
     * Set the merchant name.
     *
     * @param string $merchantName
     * @return self
     */
    public function setMerchantName(string $merchantName): self
    {
        $this->merchantName = $merchantName;
        return $this;
    }
    /**
     * Set the transaction date.
     *
     * @param \DateTime $transactionDate
     * @return self
     */
    public function setTransactionDate(\DateTime $transactionDate): self
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }
    /**
     * Set the transaction status.
     *
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}