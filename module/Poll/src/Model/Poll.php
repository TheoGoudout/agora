<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Model;

class Poll
{
    public $id;
    public $lastModified;
    public $author;
    public $content;
    public $startDate;
    public $endDate;

    public $answerCount;
    public $answers;

    public $voteCount;
    public $votes;

    public function exchangeArray($data)
    {
        $this->id           = empty($data['id'])           ?  null : $data['id'];
        $this->lastModified = empty($data['lastModified']) ?  null : $data['lastModified'];
        $this->author       = empty($data['author'])       ?  null : $data['author'];
        $this->content      = empty($data['content'])      ?  null : $data['content'];
        $this->startDate    = empty($data['startDate'])    ?  null : $data['startDate'];
        $this->endDate      = empty($data['endDate'])      ?  null : $data['endDate'];

        $this->answerCount  = empty($data['answerCount'])  ?  null : $data['answerCount'];
        $this->answers      = empty($data['answers'])      ?  null : $data['answers'];

        $this->voteCount    = empty($data['voteCount'])    ?  null : $data['voteCount'];
        $this->votes        = empty($data['votes'])        ?  null : $data['votes'];
    }
}