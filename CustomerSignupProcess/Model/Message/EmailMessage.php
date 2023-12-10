<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Model\Message;

use MK\CustomerSignupProcess\Api\CustomerDataInterface;
use MK\CustomerSignupProcess\Api\EmailMessageInterface;
use MK\QueueRetry\Api\AttemptInterface;

class EmailMessage implements EmailMessageInterface
{
    /**
     * @var \MK\CustomerSignupProcess\Api\CustomerDataInterface
     */
    private CustomerDataInterface $customerData;

    /**
     * @var \MK\QueueRetry\Api\AttemptInterface
     */
    private AttemptInterface $attemp;

    /**
     * @inheritdoc
     */
    public function getCustomerData(): CustomerDataInterface
    {
        return $this->customerData;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerData(CustomerDataInterface $customerData): void
    {
        $this->customerData = $customerData;
    }

    /**
     * @inheritdoc
     */
    public function getAttempt(): AttemptInterface
    {
        return $this->attemp;
    }

    /**
     * @inheritdoc
     */
    public function setAttempt(AttemptInterface $attempt): void
    {
        $this->attemp = $attempt;
    }
}
