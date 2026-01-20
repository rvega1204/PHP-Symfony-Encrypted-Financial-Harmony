<?php

namespace App\Service;

use App\Document\Account;
use App\Document\Transaction;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Service for handling financial operations like account and transaction creation.
 */
class FinancialService
{
    /**
     * Constructor.
     *
     * @param DocumentManager $documentManager
     */
    public function __construct(
        private DocumentManager $documentManager
    ) {
    }

    /**
     * Creates a new account.
     *
     * @param string $customerName
     * @param string $accountNumber
     * @param float $balance
     * @param string $ssn
     * @param string $email
     * @return Account
     */
    public function createAccount(string $customerName, string $accountNumber, float $balance, string $ssn, string $email): Account
    {
        $account = new Account();
        $account->setCustomerName($customerName)
            ->setAccountNumber($accountNumber)
            ->setBalance($balance)
            ->setSsn($ssn)
            ->setEmail($email);

        $this->documentManager->persist($account);
        $this->documentManager->flush();

        return $account;
    }

    /**
     * Creates a new transaction.
     *
     * @param string $accountNumber
     * @param float $amount
     * @param string $transactionType
     * @param string $description
     * @param string $cardNumber
     * @param string $cvv
     * @param string $expiryDate
     * @param string $merchantName
     * @return Transaction
     */
    public function createTransaction(
        string $accountNumber,
        float $amount,
        string $transactionType,
        string $description,
        string $cardNumber,
        string $cvv,
        string $expiryDate,
        string $merchantName
    ): Transaction {
        $transaction = new Transaction();
        $transaction->setAccountNumber($accountNumber)
            ->setAmount($amount)
            ->setTransactionType($transactionType)
            ->setDescription($description)
            ->setCardNumber($cardNumber)
            ->setCvv($cvv)
            ->setExpiryDate($expiryDate)
            ->setMerchantName($merchantName);

        $this->documentManager->persist($transaction);
        $this->documentManager->flush();

        return $transaction;
    }

    /**
     * Finds an account by its account number.
     *
     * @param string $accountNumber
     * @return Account|null
     */
    public function findAccountByNumber(string $accountNumber): ?Account
    {
        return $this->documentManager->getRepository(Account::class)
            ->findOneBy(['accountNumber' => $accountNumber]);
    }

    /**
     * Finds accounts with a balance within a specified range.
     *
     * @param float $minBalance
     * @param float $maxBalance
     * @return array
     */
    public function findAccountsByBalanceRange(float $minBalance, float $maxBalance): array
    {
        $qb = $this->documentManager->createQueryBuilder(Account::class);
        $qb->field('balance')->gte($minBalance)->lte($maxBalance);

        return $qb->getQuery()->execute()->toArray();
    }

    /**
     * Finds transactions associated with a specific account number.
     *
     * @param string $accountNumber
     * @return array
     */
    public function findTransactionsByAccountNumber(string $accountNumber): array
    {
        return $this->documentManager->getRepository(Transaction::class)
            ->findBy(['accountNumber' => $accountNumber]);
    }

    /**
     * Finds transactions with an amount within a specified range.
     *
     * @param float $minAmount
     * @param float $maxAmount
     * @return array
     */
    public function findTransactionsByAmountRange(float $minAmount, float $maxAmount): array
    {
        $qb = $this->documentManager->createQueryBuilder(Transaction::class);
        $qb->field('amount')->gte($minAmount)->lte($maxAmount);

        return $qb->getQuery()->execute()->toArray();
    }

    /**
     * Finds an account by SSN.
     *
     * @param string $ssn
     * @return Account|null
     */
    public function findAccountBySsn(string $ssn): ?Account
    {
        return $this->documentManager->getRepository(Account::class)
            ->findOneBy(['ssn' => $ssn]);
    }

    /**
     * Retrieves all accounts.
     *
     * @return array
     */
    public function getAllAccounts(): array
    {
        return $this->documentManager->getRepository(Account::class)->findAll();
    }

    /**
     * Retrieves all transactions.
     *
     * @return array
     */
    public function getAllTransactions(): array
    {
        return $this->documentManager->getRepository(Transaction::class)->findAll();
    }
}