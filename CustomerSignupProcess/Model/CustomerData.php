<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Model;

use MK\CustomerSignupProcess\Api\CustomerDataInterface;

final class CustomerData implements CustomerDataInterface
{
    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var string
     */
    private string $email;

    /**
     * @inheritdoc
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @inheritdoc
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @inheritdoc
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
