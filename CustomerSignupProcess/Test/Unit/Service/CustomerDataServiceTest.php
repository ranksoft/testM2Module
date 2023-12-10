<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Test\Unit\Service;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use MK\CustomerSignupProcess\Api\CustomerDataServiceInterface;
use MK\CustomerSignupProcess\Service\CustomerDataService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CustomerDataServiceTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|MockObject
     */
    private ScopeConfigInterface|MockObject $configMock;

    /**
     * @var CustomerDataServiceInterface
     */
    private CustomerDataServiceInterface $customerDataService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->configMock = $this->createMock(ScopeConfigInterface::class);
        $this->customerDataService = new CustomerDataService($this->configMock);
    }

    /**
     * @return void
     */
    public function testRemoveWhitespacesFromFirstName(): void
    {
        $this->configMock->expects($this->once())
            ->method('isSetFlag')
            ->with('customer_signup_process/general/enable', ScopeInterface::SCOPE_STORE)
            ->willReturn(true);

        $firstName = '  John Doe ';
        $cleanedFirstName = 'JohnDoe';
        $actualFirstName = $firstName;
        $customerMock = $this->createMock(CustomerInterface::class);

        $customerMock->method('setFirstname')
            ->willReturnCallback(function ($name) use ($customerMock, &$actualFirstName) {
                $actualFirstName = $name;
                return $customerMock;
            });

        $customerMock->method('getFirstname')
            ->willReturnCallback(function () use (&$actualFirstName) {
                return $actualFirstName;
            });

        $result = $this->customerDataService->process($customerMock);

        $this->assertSame($cleanedFirstName, $result->getFirstname());
    }
}
