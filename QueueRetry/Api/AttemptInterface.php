<?php
declare(strict_types=1);

namespace MK\QueueRetry\Api;

interface AttemptInterface
{
    /**
     * Retrieves the current number of attempts.
     *
     * @return int
     */
    public function getAttempts(): int;

    /**
     * Sets the number of attempts.
     *
     * @param int $attempts
     * @return void
     */
    public function setAttempts(int $attempts): void;

    /**
     * Increments the attempt count by one.
     *
     * @return void
     */
    public function incrementAttempts(): void;
}
