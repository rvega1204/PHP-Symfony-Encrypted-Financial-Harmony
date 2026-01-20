<?php

namespace App\Tests\Controller;

use App\Controller\FinancialController;
use App\Document\Account;
use App\Document\Transaction;
use App\Service\FinancialService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Unit tests for FinancialController.
 * Simulates API requests and mocks the service layer.
 */
class FinancialControllerTest extends TestCase
{
    private $financialService;
    private $controller;

    /**
     * Initializes the controller with a mocked FinancialService.
     * Also mocks the container to handle the AbstractController::json() dependency.
     */
    protected function setUp(): void
    {
        $this->financialService = $this->createMock(FinancialService::class);
        $this->controller = new FinancialController($this->financialService);

        // Mock container for AbstractController helper methods (json(), etc.) if needed
        // For unit testing controllers extending AbstractController, it's often easier to test the response content directly
        // However, AbstractController methods like json() compel us to set a container or mock the logic.
        // In unit tests without boosting the kernel, purely testing logic that relies on `json()` is tricky because `setContainer` is needed.
        // A common approach for UNIT testing controllers is to not extend AbstractController or to mock the container.

        $container = $this->createMock(Container::class);
        // Return false for 'serializer' to force usage of json_encode which doesn't require a service
        $container->method('has')->willReturn(false);

        // We need to ensure the serializer or basic json_encode logic works or simply test the Returns.
        // But `json()` method uses the container to get 'serializer' service or fallback to json_encode.

        // Simpler approach for this specific test:
        // Since we are mocking, we can just instantiate the controller. 
        // BUT `json()` comes from `AbstractController` -> `Serializer` or `json_encode`.
        // To make `json()` work, we need to inject a container that has `serializer` if available, or just relies on defaults.
        // Actually, `AbstractController::json` calls `$this->container->get('serializer')` if it has it.

        $this->controller->setContainer($container);
    }

    /**
     * Tests the createAccount endpoint (POST).
     * Simulates a JSON request and verifies the 201 Created response.
     */
    public function testCreateAccount(): void
    {
        $request = Request::create('/api/harmony/accounts', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'customerName' => 'Alice',
            'accountNumber' => 'ACC123',
            'balance' => 1000.0,
            'ssn' => '999-99-9999',
            'email' => 'alice@example.com'
        ]));

        $account = new Account();
        // We need to set ID via reflection or if setter exists (no setter for ID usually)
        // For mock object we can stub getId
        $accountReflection = new \ReflectionClass($account);
        $idProperty = $accountReflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($account, 'mock-id-123');

        $this->financialService->expects($this->once())
            ->method('createAccount')
            ->with('Alice', 'ACC123', 1000.0, '999-99-9999', 'alice@example.com')
            ->willReturn($account);

        $response = $this->controller->createAccount($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals('mock-id-123', $content['accountId']);
    }

    /**
     * Tests getAccountByNumber (GET).
     * Verifies successful data retrieval and 200 OK response.
     */
    public function testGetAccountByNumber(): void
    {
        $account = new Account();
        $account->setCustomerName('Bob')
            ->setAccountNumber('ACC456')
            ->setBalance(500.0)
            ->setEmail('bob@example.com');

        // Mock created at
        $accountReflection = new \ReflectionClass($account);
        $idProperty = $accountReflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($account, 'mock-id-456');

        $this->financialService->expects($this->once())
            ->method('findAccountByNumber')
            ->with('ACC456')
            ->willReturn($account);

        $response = $this->controller->getAccountByNumber('ACC456');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals('Bob', $content['account']['customerName']);
    }

    /**
     * Tests getAccountByNumber when account is missing.
     * Verifies 404 Not Found response.
     */
    public function testGetAccountByNumberNotFound(): void
    {
        $this->financialService->expects($this->once())
            ->method('findAccountByNumber')
            ->with('UNKNOWN')
            ->willReturn(null);

        $response = $this->controller->getAccountByNumber('UNKNOWN');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertFalse($content['success']);
    }
}
