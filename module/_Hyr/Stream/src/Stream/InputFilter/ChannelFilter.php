<?php
namespace Stream\InputFilter;

use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Filter\ToInt;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;
use Zend\Validator\Uri;

class ChannelFilter extends InputFilter
{
    public function __construct()
    {
        $factory = new InputFactory();

        $this->add($factory->createInput([
            'name' => 'id',
            'required' => true,
            'filters' => [
                [
                    'name' => ToInt::class,
                ],
            ],
        ]));

        $this->add($factory->createInput([
            'name' => 'user',
            'required' => true,
            'filters' => [
                [
                    'name' => StripTags::class,
                ],
                [
                    'name' => StringTrim::class,
                ],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'max' => 100,
                    ],
                ],
            ],
        ]));

        $this->add($factory->createInput([
            'name' => 'url',
            'required' => true,
            'filters' => [
                [
                    'name' => StripTags::class,
                ],
                [
                    'name' => StringTrim::class,
                ],
            ],
            'validators' => [
                [
                    'name' => Uri::class
                ],
            ],
        ]));

        $this->add($factory->createInput([
            'name' => 'description',
            'required' => false,
            'filters' => [
                [
                    'name' => StripTags::class,
                ],
                [
                    'name' => StringTrim::class,
                ],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'max' => 100,
                    ],
                ],
            ],
        ]));
    }
}
