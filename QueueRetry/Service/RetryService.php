<?php
declare(strict_types=1);

namespace MK\QueueRetry\Service;

use Magento\Framework\MessageQueue\PublisherInterface;
use MK\QueueRetry\Api\AttemptMessageInterface;
use MK\QueueRetry\Api\RetryServiceInterface;
use Psr\Log\LoggerInterface;

class RetryService implements RetryServiceInterface
{
    /**
     * @var PublisherInterface
     */
    private PublisherInterface $publisher;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param PublisherInterface $publisher
     * @param LoggerInterface $logger
     */
    public function __construct(
        PublisherInterface $publisher,
        LoggerInterface $logger
    ) {
        $this->publisher = $publisher;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $topic, AttemptMessageInterface $message, int $maxRetry = self::MAX_RETRIES): void
    {
        $attempts = $message->getAttempt()->getAttempts();

        if ($attempts >= $maxRetry) {
            $this->logMaxRetryExceeded($topic, $message, $maxRetry);
            return;
        }

        $message->getAttempt()->incrementAttempts();
        $this->publisher->publish($topic, $message);
    }

    /**
     * Logs when the maximum number of retries has been exceeded.
     *
     * @param string $topic
     * @param AttemptMessageInterface $message
     * @param int $maxRetry
     */
    private function logMaxRetryExceeded(string $topic, AttemptMessageInterface $message, int $maxRetry): void
    {
        $this->logger->error('Max retry limit reached for message.', [
            'topic' => $topic,
            'message_class' => \get_class($message),
            'maxRetry' => $maxRetry,
            'currentAttempts' => $message->getAttempt()->getAttempts()
        ]);
    }
}
