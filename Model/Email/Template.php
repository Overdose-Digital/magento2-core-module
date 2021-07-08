<?php
/**
 * Copyright © Overdose Digital. All rights reserved.
 */

namespace Overdose\Core\Model\Email;

/**
 * Class Template
 * @package Overdose\Core\Model\Email
 */
class Template
{
    /**
     * @var array
     */
    private $vars;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $templateId;

    /**
     * @var int
     */
    private $id;

    /**
     * @param array $vars
     * @return void
     */
    public function setTemplateVars(array $vars)
    {
        $this->vars = $vars;
    }

    /**
     * @param array $options
     * @return void
     */
    public function setTemplateOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getTemplateVars()
    {
        return $this->vars;
    }

    /**
     * @return array
     */
    public function getTemplateOptions()
    {
        return $this->options;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setTemplateId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTemplateId()
    {
        return $this->id;
    }
}
