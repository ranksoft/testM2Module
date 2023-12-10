<?php
declare(strict_types=1);

namespace MK\CustomerSignupProcess\Service;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use MK\CustomerSignupProcess\Api\CustomerDataInterface;
use MK\CustomerSignupProcess\Api\CustomerDataServiceInterface;
use MK\CustomerSignupProcess\Api\CustomerNotificationServiceInterface;
use Psr\Log\LoggerInterface;

class CustomerNotificationService implements CustomerNotificationServiceInterface
{
    protected const EMAIL_TEMPLATE_NAME = 'customer_data_registration_notification';
    protected const SUPPORT_EMAIL_PATH = 'trans_email/ident_support/email';
    protected const SUPPORT_NAME_PATH = 'trans_email/ident_support/name';

    /**
     * @var TransportBuilder
     */
    private TransportBuilder $transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function notify(CustomerDataInterface $customerData): void
    {
        if (!$this->isNotificationEnabled()) {
            return;
        }

        try {
            $this->sendEmailNotification($customerData);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }
    }

    /**
     * Sends the email notification with customer data.
     *
     * @param \MK\CustomerSignupProcess\Api\CustomerDataInterface $customerData
     * @throws LocalizedException
     */
    private function sendEmailNotification(CustomerDataInterface $customerData): void
    {
        $recipientEmail = $this->scopeConfig->getValue(
            self::SUPPORT_EMAIL_PATH,
            ScopeInterface::SCOPE_STORE
        );
        $recipientName = $this->scopeConfig->getValue(
            self::SUPPORT_NAME_PATH,
            ScopeInterface::SCOPE_STORE
        );
        $senderIdentity = ['email' => $recipientEmail, 'name' => $recipientName];

        $transport = $this->transportBuilder
            ->setTemplateIdentifier(static::EMAIL_TEMPLATE_NAME)
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => Store::DEFAULT_STORE_ID,
            ])
            ->setTemplateVars([
                'email' => $customerData->getEmail(),
                'firstname' => $customerData->getFirstName(),
                'lastname' => $customerData->getLastName(),
            ])
            ->setFromByScope($senderIdentity)
            ->addTo($recipientEmail, $recipientName)
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * Checks if the notification feature is enabled.
     *
     * @return bool
     */
    private function isNotificationEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            CustomerDataServiceInterface::CONFIG_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }
}
