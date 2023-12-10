<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Api;

use Magento\Customer\Api\Data\CustomerInterface;

interface CustomerDataServiceInterface
{
    public const CONFIG_PATH_ENABLE = 'customer_signup_process/general/enable';

    /**
     * Processes the provided customer data and returns the updated customer object.
     *
     * @param CustomerInterface $customer
     * @return CustomerInterface
     */
    public function process(CustomerInterface $customer): CustomerInterface;
}
