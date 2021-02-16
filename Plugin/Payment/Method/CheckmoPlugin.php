<?php

namespace Overdose\Core\Plugin\Payment\Method;

use Magento\OfflinePayments\Model\Checkmo;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Overdose\Core\Model\Config\ClientIp;

/**
 * Class CheckmoPlugin
 */
class CheckmoPlugin
{
    const XML_PATH_ALLOWED_IPS = 'payment/checkmo/allowed_ips';

    /**
     * @var ClientIp
     */
    private $clientIp;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var AdminSession
     */
    private $adminSession;

    /**
     * CheckmoPlugin constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ClientIp $clientIp
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ClientIp $clientIp
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->clientIp = $clientIp;
    }

    /**
     * Enable Check/Money Order Payment Method
     *
     * @param Checkmo $subject
     * @param $result
     * @return bool
     */
    public function afterIsActive(Checkmo $subject, $result)
    {
        if (!$result) {
            $clientIp = $this->clientIp->getClientIp();

            $allowedIps = explode(',',
                $this->scopeConfig->getValue(
                self::XML_PATH_ALLOWED_IPS,
                \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES
                )
            );

            if (in_array($clientIp, $allowedIps)) {
                $result = true;
            }
        }
        return $result;
    }
}
