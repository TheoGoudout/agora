<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Controller;

use I18n\Controller\AbstractI18nActionController;
use Zend\I18n\Translator\TranslatorInterface;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;

use Petition\Model\PetitionTable;

class PetitionStatusController extends AbstractI18nActionController
{
    protected $petitionStatusTable;

    public function __construct(
        TranslatorInterface $translator,
        PetitionStatusTable $table)
    {
        parent::__construct($translator);

        $this->petitionStatusTable = $table;
    }
}
