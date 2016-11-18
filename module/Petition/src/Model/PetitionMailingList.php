<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

use I18n\Model\I18nModel;
use Zend\I18n\Translator\TranslatorInterface;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

use Zend\Validator;

class PetitionMailingList extends I18nModel
{
    public $id;
    public $pid;
    public $lastModified;
    public $email;
    public $enabled;

    protected $inputFilter;

    public function __construct(
        TranslatorInterface $translator)
    {
        parent::__construct($translator);
    }

    public function exchangeArray($data)
    {
        $this->id               = empty($data['id'])               ?  null : $data['id'];
        $this->pid              = empty($data['pid'])              ?  null : $data['pid'];
        $this->lastModified     = empty($data['lastModified'])     ?  null : $data['lastModified'];
        $this->email            = empty($data['email'])            ?  null : $data['email'];
        $this->enabled          = empty($data['enabled'])          ?  null : $data['enabled'];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception($this->tr("Non implémentée"));
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'pid',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $errorMessage = $this->tr('Votre adresse e-mail n\'est pas valide');
            $inputFilter->add(array(
                'name'     => 'email',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'useDomainCheck' => true,
                            'messages' => array(
                                Validator\EmailAddress::INVALID            => $errorMessage,
                                Validator\EmailAddress::INVALID_FORMAT     => $errorMessage,
                                Validator\EmailAddress::INVALID_HOSTNAME   => $errorMessage,
                                Validator\EmailAddress::INVALID_MX_RECORD  => $errorMessage,
                                Validator\EmailAddress::INVALID_SEGMENT    => $errorMessage,
                                Validator\EmailAddress::DOT_ATOM           => $errorMessage,
                                Validator\EmailAddress::QUOTED_STRING      => $errorMessage,
                                Validator\EmailAddress::INVALID_LOCAL_PART => $errorMessage,
                                Validator\EmailAddress::LENGTH_EXCEEDED    => $errorMessage,

                                Validator\Hostname::CANNOT_DECODE_PUNYCODE  => null,
                                Validator\Hostname::INVALID                 => null,
                                Validator\Hostname::INVALID_DASH            => null,
                                Validator\Hostname::INVALID_HOSTNAME        => null,
                                Validator\Hostname::INVALID_HOSTNAME_SCHEMA => null,
                                Validator\Hostname::INVALID_LOCAL_NAME      => null,
                                Validator\Hostname::INVALID_URI             => null,
                                Validator\Hostname::IP_ADDRESS_NOT_ALLOWED  => null,
                                Validator\Hostname::LOCAL_NAME_NOT_ALLOWED  => null,
                                Validator\Hostname::UNDECIPHERABLE_TLD      => null,
                                Validator\Hostname::UNKNOWN_TLD             => null,
                            ),
                        ),
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}