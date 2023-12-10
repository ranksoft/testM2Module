<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Api;

use Magento\Customer\Api\Data\CustomerInterface;

interface CustomerSignupProcessDataPublisherInterface
{
    /**
     * Executes the signup process for the given customer.
     *
     * @param CustomerInterface $customer
     * @return void
     */
    public function execute(CustomerInterface $customer): void;
}
