<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Mindy\Bundle\SeoBundle\Model\Seo;
use Mindy\Orm\Orm;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SeoFormTypeTest extends TypeTestCase
{
    private $validator;

    public function setUp()
    {
        parent::setUp();
        $connection = $this
            ->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platform = $this
            ->getMockBuilder(AbstractPlatform::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connection->method('getDatabasePlatform')->willReturn($platform);
        Orm::setDefaultConnection($connection);
    }

    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return [
            new ValidatorExtension($this->validator),
        ];
    }

    public function testFormHasCanonical()
    {
        $source = $this
            ->getMockBuilder(PageMock::class)
            ->disableOriginalConstructor()
            ->getMock();
        $source->method('getIsNewRecord')->willReturn(false);

        $form = $this->factory->create(SeoTestForm::class, $source);
        $this->assertTrue($form->get('seo')->has('canonical'));
    }

    public function testFormWithoutCanonical()
    {
        $source = $this
            ->getMockBuilder(PageMock::class)
            ->disableOriginalConstructor()
            ->getMock();
        $source->method('getIsNewRecord')->willReturn(true);

        $form = $this->factory->create(SeoTestForm::class, $source);
        $this->assertFalse($form->get('seo')->has('canonical'));
    }
}
