<?php
/**
 * Copyright © Overdose Digital. All rights reserved.
 */

namespace Overdose\Core\Model\Email;

use Magento\Framework\Mail\Template\TransportBuilder;

/**
 * Class SenderBuilder
 * @package Overdose\Core\Model\Email
 */
class SenderBuilder
{
    /**
     * @var Template
     */
    private $templateContainer;

    /**
     * @var Identity
     */
    private $identityContainer;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @param Template $templateContainer
     * @param Identity $identityContainer
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        Template $templateContainer,
        Identity $identityContainer,
        TransportBuilder $transportBuilder
    ) {
        $this->templateContainer = $templateContainer;
        $this->identityContainer = $identityContainer;
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function send()
    {
        $this->configureEmailTemplate();
        $this->transportBuilder->addTo($this->identityContainer->getEmailReceiver(), 'Admin');
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function configureEmailTemplate()
    {
        $this->transportBuilder->setTemplateIdentifier($this->templateContainer->getTemplateId());
        $this->transportBuilder->setTemplateOptions($this->templateContainer->getTemplateOptions());
        $this->transportBuilder->setTemplateVars($this->templateContainer->getTemplateVars());
        $this->transportBuilder->setFromByScope(
            $this->identityContainer->getEmailIdentity(),
            $this->identityContainer->getStore()->getId()
        );
    }
}
