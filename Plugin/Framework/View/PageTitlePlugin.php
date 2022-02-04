<?php
declare(strict_types=1);

namespace Overdose\Core\Plugin\Framework\View;

use Magento\Framework\View\Page\Title;
use Overdose\Core\Model\Config\EnvironmentConfig;

class PageTitlePlugin
{
    /**
     * @var EnvironmentConfig
     */
    private EnvironmentConfig $environmentConfig;

    /**
     * @param EnvironmentConfig $environmentConfig
     */
    public function __construct(
        EnvironmentConfig $environmentConfig
    ) {
        $this->environmentConfig = $environmentConfig;
    }

    /**
     * @param Title $title
     * @param string $result
     * @return string
     */
    public function afterGet(Title $title, string $result): string
    {
        return $this->attachPrefix($result);
    }

    /**
     * @param Title $title
     * @param string $result
     * @return string
     */
    public function afterGetShort(Title $title, string $result): string
    {
        return $this->attachPrefix($result);
    }

    /**
     * @param string $title
     * @return string
     */
    private function attachPrefix(string $title): string
    {
        $titlePrefix = $this->environmentConfig->getHeaderTitlePrefix();

        return mb_convert_case(($titlePrefix ? ($titlePrefix . ': ' . $title) : $title), MB_CASE_TITLE, 'UTF-8');
    }
}
