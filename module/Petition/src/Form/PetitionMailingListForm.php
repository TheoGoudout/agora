<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Petition\Form;

use I18n\Form\I18nForm;
use Zend\I18n\Translator\TranslatorInterface;

use Zend\Form\Element;

class PetitionMailingListForm extends I18nForm
{
    public function __construct(
        TranslatorInterface $translator)
    {
        parent::__construct($translator, 'PetitionMailingList');

        // Petition ID
        $this->add(array(
            'name' => 'pid',
            'type' => 'Hidden',
        ));

        // E-mail
        $email = new Element\Email('email');
        $email->setLabel($this->tr('Souhaitez-vous vous inscrire à la newsletter de cette pétition? Vous ne recevrez que des informations sur cette pétition et rien d\'autre! Nous avons une politique très stricte vis-à-vis du spam!'));
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