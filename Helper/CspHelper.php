<?php


namespace Overdose\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class CspHelper
 */
class CspHelper extends AbstractHelper
{
    /**
     * @var string
     */
    const XML_PATH_SOURCES  = 'od_csp/custom_policy/rules';

    /**
     * @var Json
     */
    private $json;

    /**
     * CspHelper constructor.
     * @param Context $context
     * @param Json $json
     */
    public function __construct(
        Context $context,
        Json $json
    ) {
        $this->json = $json;

        parent::__construct($context);
    }

    /**
     * @return array
     */
    public function getCspSources()
    {
        return $this->toArray($this->getConfig());
    }

    /**
     * @return mixed
     */
    private function getConfig()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SOURCES, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $json
     * @return array
     */
    private function toArray($json)
    {
        return $this->json->unserialize($this->getConfig());
    }
}
