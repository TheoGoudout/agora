<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Controller;

use I18n\Controller\AbstractI18nActionController;
use Zend\I18n\Translator\TranslatorInterface;

use Poll\Model\PollTable;

use Zend\View\Model\ViewModel;

class PollController extends AbstractI18nActionController
{
    protected $pollTable;

    public function __construct(
        TranslatorInterface $translator,
        PollTable           $table)
    {
        parent::__construct($translator);

        $this->pollTable = $table;
    }

    public function indexAction()
    {
        $pid = $this->params()->fromRoute('pid', 0);
        $poll = $this->pollTable->getPolls(array('id' => $pid, 'answers' => true));

        if (count($poll) !== 1) {
            throw new \Exception($this->tr("Impossible de trouver le vote à l'ID $pid"));
        } else {
            $poll = $poll[0];
        }

        return new ViewModel(array(
            'poll' => $poll,
        ));
    }
}
