<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
/**
 * The ImageForm form model is used for uploading an image file.
 */
class MainForm extends Form
{
    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Constructor.
     */
    public function __construct($scenario = 'create', $entityManager = null)
    {
        // Define form name
        parent::__construct('main-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->scenario = $scenario;
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
        // Add "text" field
        $this->add([
            'type'  => 'textarea',
            'name' => 'text',
            'options' => [
                'label' => 'Text',
            ],
        ]);

        // Add "title" field
        $this->add([
            'type'  => 'text',
            'name' => 'title',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        // Add "file" field
        $this->add([
            'type'  => 'file',
            'name' => 'image',
            'attributes' => [
                'id' => 'file'
            ],
            'options' => [
                'label' => 'Image file',
            ],
        ]);

        // Add "file" field
        $this->add([
            'type'  => 'file',
            'name' => 'file',
            'attributes' => [
                'id' => 'file'
            ],
            'options' => [
                'label' => 'PDF file',
            ],
        ]);
        if ($this->scenario == 'create') {

            // Add "type" field
            $this->add([
                'type'  => 'select',
                'name' => 'type',
                'options' => [
                    'label' => 'Type',
                ],
            ]);

            // Add "type" field
            $this->add([
                'type'  => 'select',
                'name' => 'lang',
                'options' => [
                    'label' => 'Language',
                    'value_options' => [
                        1 => 'en',
                        2 => 'hy'
                    ]
                ],
            ]);
        }


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

        // Add validation rules for the "file" field
        $inputFilter->add([
            'type'     => FileInput::class,
            'name'     => 'file',
            'required' => false,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                [
                    'name'    => 'FileMimeType',
                    'options' => [
                        'mimeType'  => ['application/pdf', 'text/xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
                    ]
                ],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target'=>'./data/upload/files',
                        'useUploadName'=>true,
                        'useUploadExtension'=>true,
                        'overwrite'=>true,
                        'randomize'=>false
                    ]
                ]
            ],
        ]);

        // Add validation rules for the "file" field
        $inputFilter->add([
            'type'     => FileInput::class,
            'name'     => 'image',
            'required' => false,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                [
                    'name'    => 'FileMimeType',
                    'options' => [
                        'mimeType'  => ['image/jpeg', 'image/png']
                    ]
                ],
                ['name'    => 'FileIsImage'],
                [
                    'name'    => 'FileImageSize',
                    'options' => [
                        'minWidth'  => 128,
                        'minHeight' => 128,
                        'maxWidth'  => 4096,
                        'maxHeight' => 4096
                    ]
                ],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target'=>'./data/upload/images',
                        'useUploadName'=>true,
                        'useUploadExtension'=>true,
                        'overwrite'=>true,
                        'randomize'=>false
                    ]
                ]
            ],
        ]);

        // Add input for "full_name" field
        $inputFilter->add([
            'name'     => 'title',
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

        // Add input for "full_name" field
        $inputFilter->add([
            'name'     => 'text',
            'required' => false,
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

        if ($this->scenario == 'create') {

            // Add input for "status" field
            $inputFilter->add([
                'name' => 'type',
                'required' => true,
                'filters' => [
                    ['name' => 'ToInt'],
                ]
            ]);

            // Add input for "status" field
            $inputFilter->add([
                'name' => 'lang',
                'required' => true,
                'filters' => [
                    ['name' => 'ToInt'],
                ],
                'validators' => [
                    ['name' => 'InArray', 'options' => ['haystack' => [1, 2]]]
                ],
            ]);
        }
    }
}