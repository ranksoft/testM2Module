<?php
declare(strict_types=1);

namespace MK\QueueRetry\Api;

interface RetryServiceInterface
{
    public const MAX_RETRIES = 1;

    /**
     * Executes a retry process for a given message on a specified topic, with a maximum number of retries.
     *
     * @param string $topic
     * @param \MK\QueueRetry\Api\AttemptMessageInterface $message
     * @param int $maxRetry
     * @return void
     */
    public function execute(string $topic, AttemptMessageInterface $message, int $maxRetry = self::MAX_RETRIES): void;
}
