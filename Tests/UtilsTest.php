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
use Mindy\Bundle\SeoBundle\Util\SeoUtil;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function testKeywords()
    {
        $s = new SeoUtil(['hello']);
        $this->assertSame('привет,world', $s->generateKeywords('Привет привет foo bar  hello world'));

        $s = new SeoUtil();
        $this->assertSame('привет,hello,world', $s->generateKeywords('Привет привет foo bar  hello world'));
        $this->assertSame('привет', $s->generateKeywords('Привет привет foo bar  hello world', 10));
        $this->assertSame('привет', $s->generateKeywords('Привет привет foo bar  hello world', 10));
        $this->assertSame('Привет привет', $s->generateTitle('Привет привет foo bar  hello world', 13));
        $this->assertSame('Привет привет', $s->generateDescription('Привет привет foo bar  hello world', 13));
    }

    public function testFill()
    {
        $connection = $this
            ->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $platform = $this
            ->getMockBuilder(AbstractPlatform::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connection->method('getDatabasePlatform')->willReturn($platform);

        $source = $this
            ->getMockBuilder(PageMock::class)
            ->disableOriginalConstructor()
            ->getMock();
        $source->method('getConnection')->willReturn($connection);
        $source->method('getIsNewRecord')->willReturn(false);

        $seo = new Seo();
        $seo->setConnection($connection);

        $util = new SeoUtil();
        $this->assertNull($seo->title);
        $this->assertNull($seo->keywords);
        $this->assertNull($seo->description);
        $this->assertNull($seo->canonical);
        $this->assertNull($seo->url);

        $source->method('getTitleSource')->willReturn('foo');
        $source->method('getKeywordsSource')->willReturn('foobar hello world');
        $source->method('getDescriptionSource')->willReturn('foo');
        $source->method('getAbsoluteUrl')->willReturn('/foo/');
        $source->method('getCanonicalSource')->willReturn('/foo/');

        $util->fillFromSource($seo, $source);
        $this->assertSame('foo', $seo->title);
        $this->assertSame('foobar,hello,world', $seo->keywords);
        $this->assertSame('foo', $seo->description);
        $this->assertSame('/foo/', $seo->canonical);
        $this->assertSame('/foo/', $seo->url);
    }
}
