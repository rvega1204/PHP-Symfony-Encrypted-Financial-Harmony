<?php

namespace App\Tests\Service;

use App\Document\Account;
use App\Document\Transaction;
use App\Service\FinancialService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Query\Query;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FinancialService using mocks.
 * Ensures business logic works without database connectivity.
 */
class FinancialServiceTest extends TestCase
{
    private $documentManager;
    private $financialService;

    /**
     * Sets up the test environment with a mock DocumentManager.
     */
    protected function setUp(): void
    {
        $this->documentManager = $this->createMock(DocumentManager::class);
        $this->financialService = new FinancialService($this->documentManager);
    }

    /**
     * Tests successful account creation.
     * Verifies that persist and flush are called on the DocumentManager.
     */
    public function testCreateAccount(): void
    {
        $this->documentManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Account::class));

        $this->documentManager->expects($this->once())
            ->method('flush');

        $account = $this->financialService->createAccount(
            'John Doe',
            '1234567890',
            100.0,
            '123-456-789',
            'john@example.com'
        );

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('John Doe', $account->getCustomerName());
        $this->assertEquals('1234567890', $account->getAccountNumber());
        $this->assertEquals(100.0, $account->getBalance());
    }

    /**
     * Tests finding an account by its number.
     * Mocks the repository behavior to return an expected Account object.
     */
    public function testFindAccountByNumber(): void
    {
        $account = new Account();
        $account->setAccountNumber('1234567890');

        $repository = $this->createMock(DocumentRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['accountNumber' => '1234567890'])
            ->willReturn($account);

        $this->documentManager->expects($this->once())
            ->method('getRepository')
            ->with(Account::class)
            ->willReturn($repository);

        $result = $this->financialService->findAccountByNumber('1234567890');

        $this->assertSame($account, $result);
    }

    /**
     * Tests transaction creation.
     * Verifies correct data mapping and persistence.
     */
    public function testCreateTransaction(): void
    {
        $this->documentManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Transaction::class));

        $this->documentManager->expects($this->once())
            ->method('flush');

        $transaction = $this->financialService->createTransaction(
            '1234567890',
            50.0,
            'payment',
            'Test Transaction',
            '1234-5678-9012-3456',
            '123',
            '12/25',
            'Test Merchant'
        );

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('1234567890', $transaction->getAccountNumber());
        $this->assertEquals(50.0, $transaction->getAmount());
    }
}
