<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Poll\Model;

class PollVote
{
    public $id;
    public $pid;
    public $aid;
    public $creationDate;
    public $lastModified;

    public $ipAddress;
    public $validationValue;
    public $validationStatus;

    public function exchangeArray($data)
    {
        $this->id           = empty($data['id'])           ?  null : $data['id'];
        $this->pid          = empty($data['pid'])          ?  null : $data['pid'];
        $this->aid          = empty($data['aid'])          ?  null : $data['aid'];
        $this->creationDate = empty($data['creationDate']) ?  null : $data['creationDate'];
        $this->lastModified = empty($data['lastModified']) ?  null : $data['lastModified'];

        $this->ipAddress        = empty($data['ipAddress'])        ?  null : $data['ipAddress'];
        $this->validationValue  = empty($data['validationValue'])  ?  null : $data['validationValue'];
        $this->validationStatus = empty($data['validationStatus']) ?  null : $data['validationStatus'];
    }
}