<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Tests;

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
}
