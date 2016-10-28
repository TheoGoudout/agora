<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class PetitionSignatureForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('PetitionSignature');

        // Petition ID
        $this->add(array(
            'name' => 'pid',
            'type' => 'Hidden',
        ));

        // Gender
        $gender = new Element\Radio('gender');
        $gender->setLabel('Titre');
        $gender->setValueOptions(array(
            '0' => 'Madame',
            '1' => 'Monsieur',
        ));
        $this->add($gender);

        // First Name
        $firstName = new Element\Text('firstName');
        $firstName->setLabel('Prénom');
        $this->add($firstName);

        // Last Name
        $lastName = new Element\Text('lastName');
        $lastName->setLabel('Nom');
        $this->add($lastName);

        // Address 1
        $address1 = new Element\Text('address1');
        $address1->setLabel('Adresse ligne 1');
        $this->add($address1);

        // Address 2
        $address2 = new Element\Text('address2');
        $address2->setLabel('Adresse ligne 2');
        $this->add($address2);

        // Address 3
        $address3 = new Element\Text('address3');
        $address3->setLabel('Adresse ligne 3');
        $this->add($address3);

        // Zip Code
        $zipCode = new Element\Text('zipCode');
        $zipCode->setLabel('Code postal');
        $this->add($zipCode);

        // City
        $city = new Element\Text('city');
        $city->setLabel('Ville');
        $this->add($city);

        // Check box
        $checkbox = new Element\Checkbox('agreement');
        $checkbox->setLabel('En signant éléctroniquement cette pétition je reconnais avoir pris connaissance de cele-ci et jure sur l\'honneur être de nationalité française ou résider régulièrement en France. Je m\'engage aussi à faire parvenir une copie papier signée de cette pétition à Agora Pétition dans le cas ou celle-ci dépasse les 500 000 signatures éléctroniques.');
        $checkbox->setUseHiddenElement(true);
        $checkbox->setCheckedValue("agreed");
        $checkbox->setUncheckedValue("denied");
        $this->add($checkbox);

        // Submit button
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}