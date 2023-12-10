<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Service;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use MK\CustomerSignupProcess\Api\CustomerDataServiceInterface;
use MK\CustomerSignupProcess\Api\CustomerLogServiceInterface;
use Psr\Log\LoggerInterface;

class CustomerLogService implements CustomerLogServiceInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function log(CustomerInterface $customer): void
    {
        if (!$this->isLoggingEnabled()) {
            return;
        }

        $this->performLogging($customer);
    }

    /**
     * Performs the actual logging of customer data.
     *
     * @param CustomerInterface $customer
     */
    private function performLogging(CustomerInterface $customer): void
    {
        $this->logger->info('Customer Signup Data: ', [
            'date' => date("Y-m-d H:i:s"),
            'firstname' => $customer->getFirstname(),
            'lastname' => $customer->getLastname(),
            'email' => $customer->getEmail()
        ]);
    }

    /**
     * Checks if the logging is enabled in the store configuration.
     *
     * @return bool
     */
    private function isLoggingEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            CustomerDataServiceInterface::CONFIG_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }
}
