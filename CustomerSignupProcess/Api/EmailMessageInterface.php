<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Api;

use MK\QueueRetry\Api\AttemptMessageInterface;

interface EmailMessageInterface extends AttemptMessageInterface
{
    /**
     * Retrieves the customer data associated with the email message.
     *
     * @return \MK\CustomerSignupProcess\Api\CustomerDataInterface
     */
    public function getCustomerData(): CustomerDataInterface;

    /**
     * Sets the customer data for the email message.
     *
     * @param \MK\CustomerSignupProcess\Api\CustomerDataInterface $customerData
     * @return void
     */
    public function setCustomerData(CustomerDataInterface $customerData): void;
}
