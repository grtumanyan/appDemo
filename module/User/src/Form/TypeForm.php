<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
/**
 * The ImageForm form model is used for uploading an image file.
 */
class TypeForm extends Form
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Constructor.
     */
    public function __construct($entityManager = null)
    {
        // Define form name
        parent::__construct('type-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->entityManager = $entityManager;

        // Set binary content encoding
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {

        // Add "title" field
        $this->add([
            'type'  => 'text',
            'name' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Add',
                'id' => 'submitbutton',
            ],
        ]);

    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        $inputFilter = $this->getInputFilter();

        // Add input for "full_name" field
        $inputFilter->add([
            'name'     => 'text',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 512
                    ],
                ],
            ],
        ]);
    }
}