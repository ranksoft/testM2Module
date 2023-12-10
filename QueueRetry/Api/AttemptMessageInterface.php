<?php
declare(strict_types=1);

namespace MK\QueueRetry\Api;

interface AttemptMessageInterface
{
    /**
     * Retrieves the attempt information associated with the message.
     *
     * @return \MK\QueueRetry\Api\AttemptInterface
     */
    public function getAttempt(): AttemptInterface;

    /**
     * Sets the attempt information for the message.
     *
     * @param \MK\QueueRetry\Api\AttemptInterface $attempt
     * @return void
     */
    public function setAttempt(AttemptInterface $attempt): void;
}
