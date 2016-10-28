<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class PetitionMailingListForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('PetitionMailingList');

        // Petition ID
        $this->add(array(
            'name' => 'pid',
            'type' => 'Hidden',
        ));

        // E-mail
        $email = new Element\Email('email');
        $email->setLabel('Souhaitez-vous vous inscrire à la newsletter de cette pétition? Vous ne recevrez que des informations sur cette pétition et rien d\'autre! Nous avons une politique très stricte vis-à-vis du spam!');
        $this->add($email);

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