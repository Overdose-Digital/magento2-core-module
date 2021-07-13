<?php
declare(strict_types=1);

namespace Overdose\Core\Block\Adminhtml\Csp\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Rules
 */
class Rules extends AbstractFieldArray
{
    /**
     * @var Directives
     */
    private $directivesRenderer;

protected $textAreaType = null;

    /**
     * @inheridoc
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'source',
            [
                'label' => __('Source URL'),
                'renderer' => $this->textAreaTypes()
            ]
        );
        $this->addColumn(
            'directives',
            [
                'label' => __('Directives'),
                'renderer' => $this->getDirectivesRenderer(),
                'extra_params' => 'multiple="multiple"'
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $directives = $row->getDirectives();

        if (is_array($directives)) {
            foreach ($directives as $directive) {
                $options['option_' . $directive] = "selected='selected'";
            }
        } else {
            $options['option_' . $directives] = "selected='selected'";
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface|directivesRenderer
     * @throws LocalizedException
     */
    private function getDirectivesRenderer()
    {
        if (!$this->directivesRenderer) {
            $this->directivesRenderer = $this->getLayout()->createBlock(
                Directives::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->directivesRenderer;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function textAreaTypes()
    {
        if (!$this->textAreaType) {
            $this->textAreaType = $this->getLayout()->createBlock(
                \Overdose\Core\Block\Adminhtml\Csp\Form\Field\Textarea::class,
                ''
            );
        }

        return $this->textAreaType;
    }
}
