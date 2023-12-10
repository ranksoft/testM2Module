<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Api;

use Magento\Customer\Api\Data\CustomerInterface;

interface CustomerLogServiceInterface
{
    /**
     * Logs information about the provided customer.
     *
     * @param CustomerInterface $customer
     * @return void
     */
    public function log(CustomerInterface $customer): void;
}
