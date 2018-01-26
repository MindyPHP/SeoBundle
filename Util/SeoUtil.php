<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Util;

use Mindy\Bundle\SeoBundle\Model\Seo;
use Mindy\Bundle\SeoBundle\Seo\SeoSourceInterface;
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
        return strip_tags((string) $source);
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
        return mb_substr((string) $source, 0, $length, 'UTF-8');
    }

    /**
     * @param Seo                               $seo
     * @param SeoSourceInterface|ModelInterface $source
     *
     * @throws \Exception
     *
     * @return Seo
     */
    public function fillFromSource(Seo $seo, SeoSourceInterface $source)
    {
        $attributes = [
            'title' => $this->generateTitle($source->getTitleSource()),
            'description' => $this->generateDescription($source->getDescriptionSource()),
            'keywords' => $this->generateKeywords($source->getKeywordsSource()),
        ];

        if (false === $source->getIsNewRecord()) {
            $attributes = array_merge($attributes, [
                'url' => $source->getAbsoluteUrl(),
                'canonical' => $source->getCanonicalSource(),
            ]);
        }

        foreach ($attributes as $key => $value) {
            if (empty($seo->{$key}) && false === empty($attributes[$key])) {
                $seo->setAttribute($key, $attributes[$key]);
            }
        }

        return $seo;
    }
}
