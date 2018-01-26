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
use Mindy\Bundle\SeoBundle\Seo\SeoSourceInterface;
use Mindy\Bundle\SeoBundle\Util\SeoUtil;
use Mindy\Orm\ModelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SeoFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var SeoSourceInterface|ModelInterface $instance */
        $source = $options['source'];

        if ($source->getIsNewRecord()) {
            $seo = new Seo();
            $seoUtil = new SeoUtil();
            $seoUtil->fillFromSource($seo, $source);
        } else {
            $seo = $source->fetchSeo();
        }

        $source->setSeo($seo);

        if (false === $source->getIsNewRecord()) {
            $builder
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
            'label' => false,
            'data_class' => Seo::class,
            'source' => SeoSourceInterface::class,
        ]);
    }
}
