<?php

namespace Overdose\Core\Model\Config;

use Magento\Config\Model\Config\CommentInterface;
use Magento\Framework\HTTP\PhpEnvironment\Request;

/**
 * Class ClientIp
 */
class ClientIp implements CommentInterface
{
    /**
     * @var Request
     */
    private $http;

    /**
     * ClientIp constructor.
     * @param Request $http
     */
    public function __construct(Request $http)
    {
        $this->http = $http;
    }

    /**
     * @param string $elementValue
     * @return string
     */
    public function getCommentText($elementValue)
    {
        return __('Comma separated client IP addresses that are allowed to use the Check/Money Order payment method.')
         . __(' Your current IP: ') . '<strong>' . $this->getClientIp() . '</strong>';
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->http->getClientIp();
    }
}
