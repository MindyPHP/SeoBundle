<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Tests;

use Mindy\Bundle\SeoBundle\Seo\SeoSourceInterface;
use Mindy\Bundle\SeoBundle\Seo\SeoTrait;
use Mindy\Orm\Model;

/**
 * @method \Mindy\Orm\TreeManager objects($instance = null)
 */
abstract class PageMock extends Model implements SeoSourceInterface
{
    use SeoTrait;
}
