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

            $allowedIps = $this->clientIpHelper->getAllowedIps();

            if (in_array($clientIp, $allowedIps)
                || (isset($_SERVER['HTTP_X_CLIENT_IP'])
                    && in_array($_SERVER['HTTP_X_CLIENT_IP'], $allowedIps))) {
                $result = true;
            }
        }
        return $result;
    }
}
