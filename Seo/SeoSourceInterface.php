<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Seo;

interface SeoSourceInterface
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
}
