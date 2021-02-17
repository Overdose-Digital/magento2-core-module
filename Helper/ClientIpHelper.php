<?php

namespace Overdose\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\PhpEnvironment\Request;
/**
 * Class ClientIpHelper
 */
class ClientIpHelper extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var Request
     */
    private $http;

    /**
     * ClientIpHelper constructor.
     * @param Request $http
     * @param Context $context
     */
    public function __construct(
        Request $http,
        Context $context
    ) {
        $this->http = $http;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->http->getClientIp();
    }
}
