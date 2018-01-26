<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Library;

use Mindy\Bundle\SeoBundle\Model\Template;
use Mindy\Bundle\SeoBundle\Provider\SeoProvider;
use Mindy\Template\Library\AbstractLibrary;
use Mindy\Template\TemplateEngine;
use Symfony\Component\HttpFoundation\RequestStack;

class SeoLibrary extends AbstractLibrary
{
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    protected $request;
    /**
     * @var SeoProvider
     */
    protected $seoProvider;
    /**
     * @var TemplateEngine
     */
    protected $template;

    /**
     * SeoLibrary constructor.
     *
     * @param RequestStack   $requestStack
     * @param SeoProvider    $metaProvider
     * @param TemplateEngine $template
     */
    public function __construct(RequestStack $requestStack, SeoProvider $metaProvider, TemplateEngine $template)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->seoProvider = $metaProvider;
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'render_meta' => function ($template = 'seo/meta.html') {
                $meta = $this->seoProvider->getMeta($this->request);
                if (null === $meta) {
                    $meta = [];
                }

                return $this->template->render($template, [
                    'meta' => $meta,
                ]);
            },
            'render_template' => function ($code, array $params = []) {
                /** @var Template $metaTemplate */
                $metaTemplate = Template::objects()->get(['code' => $code]);
                if (null === $metaTemplate) {
                    return '';
                }

                return $this->template->renderString($metaTemplate->content, $params);
            },
        ];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return [];
    }
}
