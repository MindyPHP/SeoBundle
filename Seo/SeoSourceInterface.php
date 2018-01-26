<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Seo;

use Mindy\Bundle\MindyBundle\Traits\AbsoluteUrlInterface;
use Mindy\Orm\ModelInterface;

interface SeoSourceInterface extends AbsoluteUrlInterface
{
    /**
     * @return string
     */
    public function getCanonicalSource();

    /**
     * @return string
     */
    public function getTitleSource();

    /**
     * @return string
     */
    public function getKeywordsSource();

    /**
     * @return string
     */
    public function getDescriptionSource();

    /**
     * @return array
     */
    public function getOgSource();

    /**
     * @return ModelInterface|null
     */
    public function getSeo();

    /**
     * @return ModelInterface|null
     */
    public function fetchSeo();
}
