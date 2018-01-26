<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Form\Type;

use Mindy\Bundle\SeoBundle\Model\Seo;
use Mindy\Bundle\SeoBundle\Provider\SeoProvider;
use Mindy\Bundle\SeoBundle\Seo\SeoSourceInterface;
use Mindy\Bundle\SeoBundle\Util\SeoUtil;
use Mindy\Orm\ModelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SeoFormType extends AbstractType
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;
    /**
     * @var SeoProvider
     */
    protected $seoProvider;

    /**
     * MetaInlineFormType constructor.
     *
     * @param RequestStack $requestStack
     * @param SeoProvider  $seoProvider
     */
    public function __construct(RequestStack $requestStack, SeoProvider $seoProvider)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->seoProvider = $seoProvider;
    }

    /**
     * @param Seo                               $seo
     * @param SeoSourceInterface|ModelInterface $source
     */
    protected function generateMeta(Seo $seo, SeoSourceInterface $source)
    {
        $helper = new SeoUtil();
        $seo->setAttributes([
            'title' => $helper->generateTitle($source->getTitleSource()),
            'description' => $helper->generateDescription($source->getDescriptionSource()),
            'keywords' => $helper->generateKeywords($source->getKeywordsSource()),
        ]);

        if (false === $source->getIsNewRecord()) {
            $seo->setAttributes([
                'url' => $source->getCanonicalSource(),
                'canonical' => $source->getCanonicalSource(),
            ]);
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var SeoSourceInterface|ModelInterface $source */
        $source = $options['source'];

        if (false === $source->getIsNewRecord()) {
            $seo = $this->seoProvider->fetchMeta($source->getCanonicalSource());
            if (null === $seo) {
                $seo = new Seo();
                $this->generateMeta($seo, $source);
            }

            $builder
                ->setData($seo)
                ->add('canonical', TextType::class, [
                    'label' => 'Абсолютный адрес (canonical)',
                    'required' => false,
                ]);
        }

        $builder
            ->add('title', TextType::class, [
                'label' => 'Заголовок (title)',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 60]),
                ],
            ])
            ->add('keywords', TextType::class, [
                'label' => 'Ключевые слова (keywords)',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 60]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание (description)',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 160]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seo::class,
            'source' => SeoSourceInterface::class,
        ]);
    }
}
