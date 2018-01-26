<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Seo;

use Mindy\Orm\ModelInterface;

/**
 * Trait SeoTrait
 */
trait SeoTrait
{
    /**
     * @return ModelInterface|SeoSourceInterface|null
     */
    public function getSeo()
    {
        return self::objects()->get([
            'url' => $this->getAbsoluteUrl(),
        ]);
    }
}
