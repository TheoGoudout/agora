<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class PetitionSignature implements InputFilterAwareInterface
{
    public $id;
    public $pid;
    public $creationDate;
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

    public function exchangeArray($data)
    {
        $this->id           = empty($data['id'])           ? null : $data['id'];
        $this->pid          = empty($data['pid'])          ? null : $data['pid'];
        $this->creationDate = empty($data['creationDate']) ? null : $data['creationDate'];
        $this->lastModified = empty($data['lastModified']) ? null : $data['lastModified'];
        $this->gender       = empty($data['gender'])       ? null : $data['gender'];
        $this->firstName    = empty($data['firstName'])    ? null : $data['firstName'];
        $this->lastName     = empty($data['lastName'])     ? null : $data['lastName'];
        $this->address1     = empty($data['address1'])     ? null : $data['address1'];
        $this->address2     = empty($data['address2'])     ? null : $data['address2'];
        $this->address3     = empty($data['address3'])     ? null : $data['address3'];
        $this->zipCode      = empty($data['zipCode'])      ? null : $data['zipCode'];
        $this->city         = empty($data['city'])         ? null : $data['city'];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
            'name'     => 'id',
            'required' => true,
            'filters'  => array(
            array('name' => 'Int'),
            ),
            ));

            $inputFilter->add(array(
            'name'     => 'artist',
            'required' => true,
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
            'name'     => 'title',
            'required' => true,
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

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}