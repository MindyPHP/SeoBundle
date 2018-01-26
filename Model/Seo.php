<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Model;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Meta.
 *
 * @property string $title
 * @property string $url
 * @property string $keywords
 * @property string $canonical
 * @property string $description
 */
class Seo extends Model
{
    public static function getFields()
    {
        return [
            'title' => [
                'class' => CharField::class,
                'length' => 60,
                'null' => true,
            ],
            'url' => [
                'class' => CharField::class,
                'unique' => true,
                'validators' => [
                    new Assert\Url(),
                ],
            ],
            'keywords' => [
                'class' => CharField::class,
                'length' => 60,
                'null' => true,
            ],
            'canonical' => [
                'class' => CharField::class,
                'unique' => true,
                'null' => true,
                'validators' => [
                    new Assert\Url(),
                ],
            ],
            'description' => [
                'class' => TextField::class,
                'length' => 160,
                'null' => true,
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->url;
    }
}
