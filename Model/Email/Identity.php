<?php
/**
 * Copyright © Overdose Digital. All rights reserved.
 * See LICENSE_OVERDOSE.txt for license details.
 */

namespace Overdose\Core\Model\Email;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Identity
 * @package Overdose\Core\Model\Email
 */
class Identity
{
    /**
     * Configuration paths
     */
    const XML_PATH_EMAIL_IDENTITY = 'admin_notification/email/identity';
    const XML_PATH_EMAIL_TEMPLATE = 'admin_notification/email/template';
    const XML_PATH_EMAIL_RECEIVER = 'admin_notification/email/receiver';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Store
     */
    private $store;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array|false
     * @throws NoSuchEntityException
     */
    public function getEmailReceiver()
    {
        $data = $this->getConfigValue(self::XML_PATH_EMAIL_RECEIVER, $this->getStore()->getStoreId());

        if (!empty($data)) {
            return array_map('trim', explode(',', $data));
        }

        return false;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getTemplateId()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_TEMPLATE, $this->getStore()->getStoreId());
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getEmailIdentity()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_IDENTITY, $this->getStore()->getStoreId());
    }

    /**
     * @param string $path
     * @param int $storeId
     * @return string
     */
    private function getConfigValue(string $path, int $storeId)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param StoreInterface|Store $store
     * @return void
     */
    public function setStore(StoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * @return StoreInterface|Store
     * @throws NoSuchEntityException
     */
    public function getStore()
    {
        if ($this->store instanceof Store) {
            return $this->store;
        }

        return $this->storeManager->getStore();
    }
}
