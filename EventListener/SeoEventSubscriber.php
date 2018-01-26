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
     *
     * @throws \Exception
     */
    public function onSave(SeoEvent $event)
    {
        $source = $event->getSource();
        $seo = $source->getSeo();
        if (null === $seo) {
            return;
        }

        $seoUtil = new SeoUtil();
        $seoUtil->fillFromSource($seo, $source);

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
        $seo = $source->fetchSeo();
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
            SeoRemoveEvent::EVENT_NAME => 'onRemove',
        ];
    }
}
