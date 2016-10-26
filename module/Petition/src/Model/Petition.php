<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

class Petition
{
    public $id;
    public $creationDate;
    public $lastModified;
    public $author;
    public $title;
    public $content;
    public $startDate;

    public $latestStatus;
    public $latestStatusDate;
    public $statuses;

    public $signatureCount;
    public $signatures;

    public $mailingListCount;
    public $mailingLists;


    public function exchangeArray($data)
    {
        $this->id               = empty($data['id'])               ?  null : $data['id'];
        $this->creationDate     = empty($data['creationDate'])     ?  null : $data['creationDate'];
        $this->lastModified     = empty($data['lastModified'])     ?  null : $data['lastModified'];
        $this->author           = empty($data['author'])           ?  null : $data['author'];
        $this->title            = empty($data['title'])            ?  null : $data['title'];
        $this->content          = empty($data['content'])          ?  null : $data['content'];
        $this->startDate        = empty($data['startDate'])        ?  null : $data['startDate'];

        $this->latestStatus     = empty($data['latestStatus'])     ?  null : $data['latestStatus'];
        $this->latestStatusDate = empty($data['latestStatusDate']) ?  null : $data['latestStatusDate'];
        $this->statuses         = empty($data['statuses'])         ?  null : $data['statuses'];

        $this->signatureCount   = empty($data['signatureCount'])   ?  null : $data['signatureCount'];
        $this->signatures       = empty($data['signatures'])       ?  null : $data['signatures'];

        $this->mailingListCount = empty($data['mailingListCount']) ?  null : $data['mailingListCount'];
        $this->mailingLists     = empty($data['mailingLists'])     ?  null : $data['mailingLists'];
    }
}