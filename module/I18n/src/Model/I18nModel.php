<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace I18n\Model;

use Zend\I18n\Translator\TranslatorInterface;

class I18nModel
{
    protected $translator;

    public function __construct(
        TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    protected function tr($str)
    {
        return $this->translator->translate($str);
    }
}