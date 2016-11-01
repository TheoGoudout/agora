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

use Petition\Model\PetitionTable;

class PetitionsController extends AbstractI18nActionController
{
    protected $petitionTable;

    public function __construct(
        TranslatorInterface $translator,
        PetitionTable       $table)
    {
        parent::__construct($translator);

        $this->petitionTable = $table;
    }

    public function indexAction()
    {
        $pid = $this->params()->fromRoute('pid', 0);
        $petitions = $this->petitionTable->getPetitions(array('limit' => 10));

        return new ViewModel(array(
            'petitions' => $petitions,
        ));
    }
}
