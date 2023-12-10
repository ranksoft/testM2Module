<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Service;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use MK\CustomerSignupProcess\Api\CustomerDataServiceInterface;

class CustomerDataService implements CustomerDataServiceInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function process(CustomerInterface $customer): CustomerInterface
    {
        if (!$this->isSignupProcessEnabled()) {
            return $customer;
        }

        $firstName = $customer->getFirstname();
        if (\str_contains($firstName, ' ')) {
            $cleanedFirstName = \str_replace(' ', '', $firstName);
            $customer->setFirstname($cleanedFirstName);
        }

        return $customer;
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
