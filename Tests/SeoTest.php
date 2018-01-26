<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 26/01/2018
 * Time: 19:34
 */

namespace Mindy\Bundle\SeoBundle\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Mindy\Bundle\SeoBundle\Model\Seo;
use Mindy\Orm\Orm;
use PHPUnit\Framework\TestCase;

class SeoTest extends TestCase
{
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

    public function testToString()
    {
        $this->assertSame('/foo/bar/', (string)new Seo(['url' => '/foo/bar/']));
    }
}
