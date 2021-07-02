<?php
/**
 * Copyright © Overdose Digital. All rights reserved.
 * See LICENSE_OVERDOSE.txt for license details.
 */

namespace Overdose\Core\Model\Email;

use Magento\Framework\App\Area;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class Sender
 * @package Overdose\Core\Model\Email
 */
class Sender
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
     * @var SenderBuilderFactory
     */
    private $senderBuilderFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Template $templateContainer
     * @param Identity $identityContainer
     * @param SenderBuilderFactory $senderBuilderFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Template $templateContainer,
        Identity $identityContainer,
        SenderBuilderFactory $senderBuilderFactory,
        LoggerInterface $logger
    ) {
        $this->templateContainer = $templateContainer;
        $this->identityContainer = $identityContainer;
        $this->senderBuilderFactory = $senderBuilderFactory;
        $this->logger = $logger;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function send(array $data)
    {
        try {
            if ($this->identityContainer->getEmailReceiver()) {
                $data['environment'] = isset($_ENV['MAGENTO_CLOUD_BRANCH']) ? ucfirst($_ENV['MAGENTO_CLOUD_BRANCH']) : 'n/a';
                $this->prepareTemplate($data);
                /** @var SenderBuilder $sender */
                $sender = $this->getSender();
                $sender->send();
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @param array $data
     * @return void
     * @throws NoSuchEntityException
     */
    protected function prepareTemplate(array $data)
    {
        $this->templateContainer->setTemplateVars($data);
        $this->templateContainer->setTemplateOptions($this->getTemplateOptions());
        $templateId = $this->identityContainer->getTemplateId();
        $this->templateContainer->setTemplateId($templateId);
    }

    /**
     * @return SenderBuilder
     */
    private function getSender()
    {
        return $this->senderBuilderFactory->create([
            'templateContainer' => $this->templateContainer,
            'identityContainer' => $this->identityContainer,
        ]);
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    private function getTemplateOptions()
    {
        return [
            'area' => Area::AREA_FRONTEND,
            'store' => $this->identityContainer->getStore()->getStoreId()
        ];
    }
}
