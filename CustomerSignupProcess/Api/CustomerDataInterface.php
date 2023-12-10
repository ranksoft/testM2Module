<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Api;

interface CustomerDataInterface
{
    /**
     * Retrieves the first name of the customer.
     *
     * @return string
     */
    public function getFirstName(): string;

    /**
     * Sets the first name of the customer.
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName): void;

    /**
     * Retrieves the last name of the customer.
     *
     * @return string
     */
    public function getLastName(): string;

    /**
     * Sets the last name of the customer.
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName): void;

    /**
     * Retrieves the email address of the customer.
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Sets the email address of the customer.
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void;
}
