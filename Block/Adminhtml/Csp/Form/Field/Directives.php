<?php
declare(strict_types=1);

namespace Overdose\Core\Block\Adminhtml\Csp\Form\Field;

use Magento\Framework\View\Element\Html\Select;
use Magento\Csp\Model\Policy\FetchPolicy;

/**
 * Class Directives
 */
class Directives extends Select
{
    /**
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getDirectivesArray());
        }
        $this->setExtraParams("multiple='multiple'");
        return parent::_toHtml();
    }

    /**
     * @return array
     */
    private function getDirectivesArray(): array
    {
        $directives = [];
        foreach (FetchPolicy::POLICIES as $policy) {
            $directives[] = ['label' => $policy, 'value' => $policy];
        }

        return $directives;
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value . '[]');
    }

    /**
     * Set "id" for <select> element
     *
     * @param $value
     * @return Directives
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }
}
