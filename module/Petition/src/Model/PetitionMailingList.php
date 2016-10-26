<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

class PetitionMailingList
{
    public $id;
    public $pid;
    public $creationDate;
    public $lastModified;
    public $email;
    public $enabled;

    public function exchangeArray($data)
    {
        $this->id               = empty($data['id'])               ?  null : $data['id'];
        $this->pid              = empty($data['pid'])              ?  null : $data['pid'];
        $this->creationDate     = empty($data['creationDate'])     ?  null : $data['creationDate'];
        $this->lastModified     = empty($data['lastModified'])     ?  null : $data['lastModified'];
        $this->email            = empty($data['email'])            ?  null : $data['email'];
        $this->enabled          = empty($data['enabled'])          ?  null : $data['enabled'];
    }
}