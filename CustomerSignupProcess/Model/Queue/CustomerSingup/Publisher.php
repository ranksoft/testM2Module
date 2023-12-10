<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Model\Queue\CustomerSingup;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use MK\CustomerSignupProcess\Api\CustomerDataInterface;
use MK\CustomerSignupProcess\Api\CustomerDataInterfaceFactory;
use MK\CustomerSignupProcess\Api\CustomerSignupProcessDataPublisherInterface;
use MK\CustomerSignupProcess\Api\EmailMessageInterface;
use MK\CustomerSignupProcess\Api\EmailMessageInterfaceFactory;
use MK\QueueRetry\Api\AttemptInterface;
use MK\QueueRetry\Api\AttemptInterfaceFactory;

class Publisher implements CustomerSignupProcessDataPublisherInterface
{
    public const TOPIC_NAME = 'mk.customer.singup.process.data';
    public const RETRY_TOPIC_NAME = 'mk.customer.singup.process.retry.data';

    /**
     * @var PublisherInterface
     */
    private PublisherInterface $publisher;

    /**
     * @var CustomerDataInterfaceFactory
     */
    private CustomerDataInterfaceFactory $customerDataFactory;

    /**
     * @var EmailMessageInterfaceFactory
     */
    private EmailMessageInterfaceFactory $messageFactory;

    /**
     * @var AttemptInterfaceFactory
     */
    private AttemptInterfaceFactory $attemptFactory;

    /**
     * @param PublisherInterface $publisher
     * @param CustomerDataInterfaceFactory $customerDataFactory
     * @param EmailMessageInterfaceFactory $messageFactory
     * @param AttemptInterfaceFactory $attemptFactory
     */
    public function __construct(
        PublisherInterface $publisher,
        CustomerDataInterfaceFactory $customerDataFactory,
        EmailMessageInterfaceFactory $messageFactory,
        AttemptInterfaceFactory $attemptFactory
    ) {
        $this->publisher = $publisher;
        $this->customerDataFactory = $customerDataFactory;
        $this->messageFactory = $messageFactory;
        $this->attemptFactory = $attemptFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute(CustomerInterface $customer): void
    {
        $customerData = $this->createCustomerData($customer);
        $attempt = $this->attemptFactory->create();
        $message = $this->createEmailMessage($customerData, $attempt);

        $this->publisher->publish(self::TOPIC_NAME, $message);
    }

    /**
     * Creates customer data object from customer entity.
     *
     * @param CustomerInterface $customer
     * @return \MK\CustomerSignupProcess\Api\CustomerDataInterface
     */
    private function createCustomerData(CustomerInterface $customer): CustomerDataInterface
    {
        $customerData = $this->customerDataFactory->create();
        $customerData->setFirstName($customer->getFirstname());
        $customerData->setLastName($customer->getLastname());
        $customerData->setEmail($customer->getEmail());

        return $customerData;
    }

    /**
     * Creates an email message object with customer data and attempt information.
     *
     * @param \MK\CustomerSignupProcess\Api\CustomerDataInterface $customerData
     * @param AttemptInterface $attempt
     * @return EmailMessageInterface
     */
    private function createEmailMessage(
        CustomerDataInterface $customerData,
        AttemptInterface $attempt
    ): EmailMessageInterface {
        $message = $this->messageFactory->create();
        $message->setCustomerData($customerData);
        $message->setAttempt($attempt);

        return $message;
    }
}
