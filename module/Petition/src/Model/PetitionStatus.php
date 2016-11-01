<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

use I18n\Model\I18nModel;
use Zend\I18n\Translator\TranslatorInterface;

class PetitionStatus extends I18nModel
{
    public $id;
    public $pid;
    public $date;
    public $content;
    public $comment;

    public function __construct(
        TranslatorInterface $translator)
    {
        parent::__construct($translator);
    }

    public function exchangeArray($data)
    {
        $this->id      = empty($data['id'])      ? null : $data['id'];
        $this->pid     = empty($data['pid'])     ? null : $data['pid'];
        $this->date    = empty($data['date'])    ? null : $data['date'];
        $this->content = empty($data['content']) ? null : $data['content'];
        $this->comment = empty($data['comment']) ? null : $data['comment'];
    }
}