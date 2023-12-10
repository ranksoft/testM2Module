<?php
declare(strict_types=1);

namespace MK\QueueRetry\Model;

use MK\QueueRetry\Api\AttemptInterface;

class Attempt implements AttemptInterface
{
    /**
     * @var int
     */
    private int $attempts = 0;

    /**
     * @inheritdoc
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * @inheritdoc
     */
    public function setAttempts(int $attempts): void
    {
        $this->attempts = $attempts;
    }

    /**
     * @inheritdoc
     */
    public function incrementAttempts(): void
    {
        $this->attempts++;
    }
}
