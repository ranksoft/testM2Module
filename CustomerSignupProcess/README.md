# Magento 2 Customer Registration Enhancement Extension

## Overview
Magento 2 developer test task.
This Magento 2 extension enhances the customer registration process by modifying the first name field and implementing post-registration actions. It ensures that the first name field is saved without any whitespaces and performs specific actions after successful customer registration.

## Features
- **First Name Modification**: Automatically removes any whitespaces from the First Name field during customer registration.
- **Logging Customer Data**: Logs customer data including current date and time, first name, last name, and email to a separate file in the `var/log` directory.
- **Email Notification**: Sends an email with customer data to the Customer Support email address configured in Magento.

## Installation
1. Extract the contents of the archive into `<magento_root>/app/code/MK`.
2. Navigate to the Magento root directory and run `php bin/magento module:enable MK_CustomerSignupProcess` to enable the module.
3. Run `php bin/magento setup:upgrade` to upgrade the database schema.
4. Clear the cache by running `php bin/magento cache:clean`.

## Configuration
- No additional configuration is required for this extension.
- Ensure that the Customer Support email is properly configured in Magento for email notifications.

## Usage
- The extension works automatically upon customer registration. No additional action is required from the admin or the customer.
- Check the `var/log` directory for customer data logs after registration.

## Acceptance Criteria Fulfillment
- **SOLID Principles**: The extension is developed adhering to SOLID principles for object-oriented design.
- **Magento 2 Best Practices**: Respects Magento 2 development best practices, ensuring compatibility and performance.
- 
## Dependencies
- Magento_Customer: Extends the Magento customer registration functionality.

## Notes
- This extension does not modify existing customer data. It only affects new registrations.

## Run Test
- command: `vendor/bin/phpunit app/code/MK/CustomerSignupProcess/Test/Unit/Service/CustomerDataServiceTest.php`