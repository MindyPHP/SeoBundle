<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Form;

use Mindy\Bundle\SeoBundle\Model\Seo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SeoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'Адрес страницы',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('canonical', TextType::class, [
                'label' => 'Абсолютный адрес (canonical)',
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'Заголовок (title)',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 60]),
                ],
            ])
            ->add('keywords', TextType::class, [
                'label' => 'Ключевые слова (keywords)',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 60]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание (description)',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 160]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seo::class,
        ]);
    }
}
