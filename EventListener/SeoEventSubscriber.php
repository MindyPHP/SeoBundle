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

use Mindy\Bundle\SeoBundle\Provider\SeoProvider;
use Mindy\Bundle\SeoBundle\Util\SeoUtil;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SeoEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var SeoProvider
     */
    protected $provider;

    /**
     * SeoListener constructor.
     *
     * @param SeoProvider $provider
     */
    public function __construct(SeoProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param SeoEvent $event
     */
    public function onSave(SeoEvent $event)
    {
        $seo = $event->getSeo();

        $seoUtil = new SeoUtil();
        $seoUtil->fillFromSource($seo);

        if (false === $seo->save()) {
            throw new \RuntimeException('Error while save seo');
        }
    }

    /**
     * @param SeoRemoveEvent $event
     */
    public function onRemove(SeoRemoveEvent $event)
    {
        $source = $event->getSource();
        $seo = $this->provider->fetchMeta($source->getAbsoluteUrl());
        if ($seo) {
            $seo->delete();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SeoEvent::EVENT_NAME => 'onSave',
            SeoRemoveEvent::EVENT_NAME => 'onRemove'
        ];
    }
}
