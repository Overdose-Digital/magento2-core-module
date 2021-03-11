<?php

namespace Overdose\Core\Model\Config\Comment\AllowedIps;

use Magento\Config\Model\Config\CommentInterface;
use Overdose\Core\Helper\ClientIpHelper;

/**
 * Class ClientIp
 */
class ClientIp implements CommentInterface
{
    /**
     * @var ClientIpHelper
     */
    protected $clientIpHelper;

    /**
     * ClientIp constructor.
     * @param ClientIpHelper $clientIpHelper
     */
    public function __construct(ClientIpHelper $clientIpHelper)
    {
        $this->clientIpHelper = $clientIpHelper;
    }

    /**
     * @param string $elementValue
     * @return string
     */
    public function getCommentText($elementValue)
    {
        return __('Comma separated client IP addresses that are allowed to use the Check/Money Order payment method.')
         . __(' Your current IP: ') . '<strong>' . $this->clientIpHelper->getClientIp() . '</strong>';
    }
}

