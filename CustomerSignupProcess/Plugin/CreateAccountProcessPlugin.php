<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Plugin;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use MK\CustomerSignupProcess\Api\CustomerDataServiceInterface;
use MK\CustomerSignupProcess\Api\CustomerLogServiceInterface;
use MK\CustomerSignupProcess\Api\CustomerSignupProcessDataPublisherInterface;
use Psr\Log\LoggerInterface;

class CreateAccountProcessPlugin
{
    /**
     * @var CustomerDataServiceInterface
     */
    private CustomerDataServiceInterface $customerDataService;

    /**
     * @var CustomerLogServiceInterface
     */
    private CustomerLogServiceInterface $customerLogService;

    /**
     * @var CustomerSignupProcessDataPublisherInterface
     */
    private CustomerSignupProcessDataPublisherInterface $publisher;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param CustomerDataServiceInterface $customerDataService
     * @param CustomerLogServiceInterface $customerLogService
     * @param CustomerSignupProcessDataPublisherInterface $publisher
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerDataServiceInterface $customerDataService,
        CustomerLogServiceInterface $customerLogService,
        CustomerSignupProcessDataPublisherInterface $publisher,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->customerDataService = $customerDataService;
        $this->customerLogService = $customerLogService;
        $this->publisher = $publisher;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Processes customer data before account creation.
     *
     * @param AccountManagementInterface $subject
     * @param CustomerInterface $customer
     * @param string|null $password
     * @param string $redirectUrl
     * @return array
     */
    public function beforeCreateAccount(
        AccountManagementInterface $subject,
        CustomerInterface $customer,
        $password = null,
        $redirectUrl = ''
    ): array {
        if (!$this->isSignupProcessEnabled()) {
            return [$customer];
        }

        try {
            if ($customer->getId() === null) {
                $customer = $this->customerDataService->process($customer);
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }

        return [$customer, $password, $redirectUrl];
    }

    /**
     * Performs actions after account creation such as logging and sending notifications.
     *
     * @param AccountManagementInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     */
    public function afterCreateAccount(
        AccountManagementInterface $subject,
        CustomerInterface $result
    ): CustomerInterface {
        if (!$this->isSignupProcessEnabled()) {
            return $result;
        }

        try {
            $this->customerLogService->log($result);
            $this->publisher->execute($result);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }

        return $result;
    }

    /**
     * Checks if the customer signup process is enabled in the store configuration.
     *
     * @return bool
     */
    private function isSignupProcessEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            CustomerDataServiceInterface::CONFIG_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }
}
