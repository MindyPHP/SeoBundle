<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Seo;

use Mindy\Bundle\SeoBundle\Model\Seo;
use Mindy\Orm\ModelInterface;

trait SeoTrait
{
    /**
     * @var Seo
     */
    protected $seo;

    /**
     * @param Seo|null $seo
     */
    public function setSeo($seo)
    {
        $this->seo = $seo;
    }

    /**
     * @return ModelInterface|null
     */
    public function fetchSeo()
    {
        return Seo::objects()->get([
            'url' => $this->getAbsoluteUrl()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getSeo()
    {
        return $this->seo;
    }
}
