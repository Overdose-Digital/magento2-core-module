<?php
declare(strict_types=1);

namespace Overdose\Core\Plugin\Framework\View;

use Magento\Framework\Escaper;
use Magento\Framework\View\Layout;
use Magento\Framework\View\Layout\Element;
use Overdose\Core\Model\Config\EnvironmentConfig;

class LayoutPlugin
{
    /**
     * @var Escaper
     */
    private Escaper $escaper;

    /**
     * @var EnvironmentConfig
     */
    private EnvironmentConfig $environmentConfig;

    /**
     * @param Escaper $escaper
     * @param EnvironmentConfig $environmentConfig
     */
    public function __construct(
        Escaper $escaper,
        EnvironmentConfig $environmentConfig
    ) {
        $this->escaper = $escaper;
        $this->environmentConfig = $environmentConfig;
    }

    /**
     * @param Layout $layout
     * @param string $html
     * @param string $name
     * @return string
     */
    public function afterRenderNonCachedElement(Layout $layout, string $html, string $name): string
    {
        if ($name !== 'header' || !$layout->isContainer($name)) {
            return $html;
        }

        $cssBackground = $this->escaper->escapeHtml($this->environmentConfig->getHeaderBackground());

        if (!$cssBackground) {
            return $html;
        }

        $cssString = '"' . $layout->getElementProperty($name, Element::CONTAINER_OPT_HTML_CLASS) . '"';
        $cssStringReplacement = $cssString . ' style="background: ' . $cssBackground . ';"';

        return $this->replaceOnce($cssString, $cssStringReplacement, $html);
    }

    /**
     * @param string $findString
     * @param string $replaceWith
     * @param string $sourceString
     * @return string
     */
    private function replaceOnce(string $findString, string $replaceWith, string $sourceString): string
    {
        $position = strpos($sourceString, $findString);

        if ($position !== false) {
            $sourceString = substr_replace($sourceString, $replaceWith, $position, strlen($findString));
        }

        return $sourceString;
    }
}
