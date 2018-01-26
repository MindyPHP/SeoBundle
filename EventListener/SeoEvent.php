<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
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
     * @var SeoSourceInterface
     */
    protected $source;

    public function __construct(SeoSourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @return SeoSourceInterface
     */
    public function getSource(): SeoSourceInterface
    {
        return $this->source;
    }
}
