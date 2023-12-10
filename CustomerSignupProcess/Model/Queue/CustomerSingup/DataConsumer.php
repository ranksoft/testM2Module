<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Model\Queue\CustomerSingup;

use MK\CustomerSignupProcess\Api\EmailMessageInterface;
use MK\CustomerSignupProcess\Service\CustomerNotificationService;
use MK\QueueRetry\Api\RetryServiceInterface;
use Psr\Log\LoggerInterface;

class DataConsumer
{
    /**
     * @var CustomerNotificationService
     */
    private CustomerNotificationService $customerNotificationService;

    /**
     * @var RetryServiceInterface
     */
    private RetryServiceInterface $retryMessage;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param CustomerNotificationService $customerNotificationService
     * @param RetryServiceInterface $retryMessage
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerNotificationService $customerNotificationService,
        RetryServiceInterface $retryMessage,
        LoggerInterface $logger
    ) {
        $this->customerNotificationService = $customerNotificationService;
        $this->retryMessage = $retryMessage;
        $this->logger = $logger;
    }

    /**
     * Processes the message from the queue, handles customer notification, and manages retries on failure.
     *
     * @param \MK\CustomerSignupProcess\Api\EmailMessageInterface $message
     */
    public function processMessage(EmailMessageInterface $message): void
    {
        try {
            $customerData = $message->getCustomerData();
            $this->customerNotificationService->notify($customerData);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
            $this->retryMessage->execute(Publisher::RETRY_TOPIC_NAME, $message, 2);
        }
    }
}
