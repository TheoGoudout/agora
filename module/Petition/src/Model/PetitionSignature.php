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
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Zend\Validator;

class PetitionSignature extends I18nModel
{
    public $id;
    public $pid;
    public $lastModified;
    public $gender;
    public $firstName;
    public $lastName;
    public $address1;
    public $address2;
    public $address3;
    public $zipCode;
    public $city;

    protected $inputFilter;

    public function __construct(
        TranslatorInterface $translator)
    {
        parent::__construct($translator);
    }

    public function exchangeArray($data)
    {
        $this->id           = isset($data['id'])           ? $data['id']           : null;
        $this->pid          = isset($data['pid'])          ? $data['pid']          : null;
        $this->lastModified = isset($data['lastModified']) ? $data['lastModified'] : null;
        $this->gender       = isset($data['gender'])       ? $data['gender']       : null;
        $this->firstName    = isset($data['firstName'])    ? $data['firstName']    : null;
        $this->lastName     = isset($data['lastName'])     ? $data['lastName']     : null;
        $this->address1     = isset($data['address1'])     ? $data['address1']     : null;
        $this->address2     = isset($data['address2'])     ? $data['address2']     : null;
        $this->address3     = isset($data['address3'])     ? $data['address3']     : null;
        $this->zipCode      = isset($data['zipCode'])      ? $data['zipCode']      : null;
        $this->city         = isset($data['city'])         ? $data['city']         : null;
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

            $inputFilter->add(array(
                'name'     => 'gender',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                Validator\NotEmpty::IS_EMPTY => $this->tr('Vous devez renseigner votre titre'),
                            ),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'firstName',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                Validator\NotEmpty::IS_EMPTY => $this->tr('Vous devez renseigner votre prénom'),
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

            $inputFilter->add(array(
                'name'     => 'lastName',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                Validator\NotEmpty::IS_EMPTY => $this->tr('Vous devez renseigner votre nom'),
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

            $inputFilter->add(array(
                'name'     => 'address1',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                Validator\NotEmpty::IS_EMPTY => $this->tr('Vous devez renseigner votre adresse'),
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

            $inputFilter->add(array(
                'name'     => 'address2',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'address3',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'zipCode',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                Validator\NotEmpty::IS_EMPTY => $this->tr('Vous devez renseigner votre code postal'),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/[0-9]*/',
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'city',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                Validator\NotEmpty::IS_EMPTY => $this->tr('Vous devez renseigner votre ville'),
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

            $inputFilter->add(array(
                'name'     => 'agreement',
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'Identical',
                        'options' => array(
                            'token' => 'agreed',
                            'messages' => array(
                                Validator\Identical::NOT_SAME => $this->tr('Vous devez accepter les termes de la signatures éléctronique'),
                            ),
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}