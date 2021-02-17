<?php

namespace Overdose\Core\Plugin\Payment\Method;

use Magento\OfflinePayments\Model\Checkmo;
use Overdose\Core\Helper\ClientIpHelper;

/**
 * Class CheckmoPlugin
 */
class CheckmoPlugin
{
    /**
     * @var string
     */
    const XML_PATH_ALLOWED_IPS = 'payment/checkmo/allowed_ips';

    /**
     * @var ClientIpHelper
     */
    private $clientIpHelper;

    /**
     * CheckmoPlugin constructor.
     * @param ClientIpHelper $clientIpHelper
     */
    public function __construct(
        ClientIpHelper $clientIpHelper
    ) {
        $this->clientIpHelper = $clientIpHelper;
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
            $clientIp = $this->clientIpHelper->getClientIp();

            $allowedIps = explode(',',
                $this->clientIpHelper->scopeConfig->getValue(
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
