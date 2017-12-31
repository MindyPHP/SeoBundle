<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Util;

use Mindy\Bundle\SeoBundle\Seo\SeoSourceInterface;
use Mindy\Bundle\MindyBundle\Traits\AbsoluteUrlInterface;
use Mindy\Orm\ModelInterface;

class SeoUtil
{
    /**
     * @var array
     */
    protected $stopWords = [];

    /**
     * SeoHelper constructor.
     *
     * @param array $stopWords
     */
    public function __construct(array $stopWords = [])
    {
        $this->stopWords = $stopWords;
    }

    /**
     * @param $source
     *
     * @return string
     */
    protected function removeHtml($source)
    {
        return strip_tags($source);
    }

    /**
     * @param $source
     * @param int $length
     *
     * @return string
     */
    public function generateDescription($source, $length = 160)
    {
        return mb_substr($this->removeHtml($source), 0, $length, 'UTF-8');
    }

    /**
     * @param $source
     * @param int $length
     * @param int $minLength
     *
     * @return string
     */
    public function generateKeywords($source, $length = 60, $minLength = 3)
    {
        // Remove all special characters to only leave alphanumeric characters (and whitespace)
        // Explode the phrase into an array, splitting by whitespace
        $keywords = preg_split('/[\\s,]+/', $this->removeHtml($source));

        // Create an empty array to store keywords
        $end = [];

        // Loop through each keyword
        foreach ($keywords as $keyword) {
            // Check that the keyword is greater than 3 characters long
            // If it is, add it to the $end array
            if (mb_strlen($keyword, 'UTF-8') <= $minLength) {
                continue;
            }

            $word = mb_strtolower($keyword, 'UTF-8');
            if (in_array($word, $this->stopWords)) {
                continue;
            }

            $end[] = $word;
        }

        $end = array_unique($end);
        while (mb_strlen(implode(',', $end), 'UTF-8') > $length) {
            $end = array_slice($end, 0, count($end) - 1);
        }

        return implode(',', $end);
    }

    /**
     * @param $source
     * @param int $length
     *
     * @return string
     */
    public function generateTitle($source, $length = 60)
    {
        return mb_substr($source, 0, $length, 'UTF-8');
    }

    /**
     * @param SeoSourceInterface|ModelInterface $source
     */
    public function fillFromSource(SeoSourceInterface $source)
    {
        $helper = new SeoUtil();
        $attributes = [
            'title' => $helper->generateTitle($source->getTitleSource()),
            'description' => $helper->generateDescription($source->getDescriptionSource()),
            'keywords' => $helper->generateKeywords($source->getKeywordsSource()),
        ];

        if ($source instanceof AbsoluteUrlInterface) {
            $attributes = array_merge($attributes, [
                'url' => $source->getCanonicalSource(),
                'canonical' => $source->getCanonicalSource(),
            ]);
        }

        if ($source->getIsNewRecord()) {
            $source->setAttributes($attributes);
        } else {
            foreach (array_keys($source->getFields()) as $field) {
                if (empty($source->{$field}) && isset($attributes[$field])) {
                    $source->setAttribute($field, $attributes[$field]);
                }
            }
        }
    }
}
