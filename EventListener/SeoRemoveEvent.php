<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\EventListener;

use Mindy\Bundle\MindyBundle\Traits\AbsoluteUrlInterface;
use Mindy\Bundle\SeoBundle\Seo\SeoSourceInterface;
use Mindy\Orm\ModelInterface;
use Symfony\Component\EventDispatcher\Event;

final class SeoRemoveEvent extends Event
{
    const EVENT_NAME = 'soe.remove';

    /**
     * @var AbsoluteUrlInterface|SeoSourceInterface|ModelInterface
     */
    protected $source;

    /**
     * @param AbsoluteUrlInterface|SeoSourceInterface|ModelInterface $source
     */
    public function __construct(SeoSourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @return AbsoluteUrlInterface|SeoSourceInterface|ModelInterface
     */
    public function getSource(): SeoSourceInterface
    {
        return $this->source;
    }
}
