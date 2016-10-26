<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Model;

class PollAnswer
{
    public $id;
    public $pid;
    public $creationDate;
    public $lastModified;
    public $author;
    public $content;

    public $voteCount;
    public $votes;

    public function exchangeArray($data)
    {
        $this->id           = empty($data['id'])           ?  null : $data['id'];
        $this->pid          = empty($data['pid'])          ?  null : $data['pid'];
        $this->creationDate = empty($data['creationDate']) ?  null : $data['creationDate'];
        $this->lastModified = empty($data['lastModified']) ?  null : $data['lastModified'];
        $this->author       = empty($data['author'])       ?  null : $data['author'];
        $this->content      = empty($data['content'])      ?  null : $data['content'];

        $this->voteCount    = empty($data['voteCount'])    ?  null : $data['voteCount'];
        $this->votes        = empty($data['votes'])        ?  null : $data['votes'];
    }
}