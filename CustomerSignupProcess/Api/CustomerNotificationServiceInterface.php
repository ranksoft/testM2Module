<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Api;

interface CustomerNotificationServiceInterface
{
    /**
     * Sends a notification based on the provided customer data.
     *
     * @param \MK\CustomerSignupProcess\Api\CustomerDataInterface $customerData
     * @return void
     */
    public function notify(CustomerDataInterface $customerData): void;
}
