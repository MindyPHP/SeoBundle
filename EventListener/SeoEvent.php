<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\EventListener;

use Mindy\Bundle\SeoBundle\Model\Seo;
use Mindy\Bundle\SeoBundle\Seo\SeoSourceInterface;
use Symfony\Component\EventDispatcher\Event;

final class SeoEvent extends Event
{
    const EVENT_NAME = 'seo.save';

    /**
     * @var Seo
     */
    protected $seo;

    /**
     * @var SeoSourceInterface
     */
    protected $source;

    public function __construct(Seo $seo, SeoSourceInterface $source)
    {
        $this->seo = $seo;
        $this->source = $source;
    }

    /**
     * @return Seo
     */
    public function getSeo(): Seo
    {
        return $this->seo;
    }

    /**
     * @return SeoSourceInterface
     */
    public function getSource(): SeoSourceInterface
    {
        return $this->source;
    }
}
