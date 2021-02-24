<?php

namespace Overdose\Core\Model\Config\Comment\Csp;

use Magento\Config\Model\Config\CommentInterface;
use Overdose\Core\Helper\ClientIpHelper;

/**
 * Class CustomCsp
 */
class CustomCsp implements CommentInterface
{
    const LINK = 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/default-src';

    /**
     * @param string $elementValue
     * @return string
     */
    public function getCommentText($elementValue)
    {
        return __('Add sources to Content-Security-Policy/Content-Security-Policy-Report-Only header.
Find more info about CSP <a href="%1" target="_blank">here</a>', self::LINK);
    }
}

