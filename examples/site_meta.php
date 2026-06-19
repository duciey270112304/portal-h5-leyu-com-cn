<?php

/**
 * SiteMeta - A simple container for site metadata and description generation.
 *
 * This file provides a structure to hold site information and a method
 * to produce a short textual summary based on that data.
 */

class SiteMeta
{
    private string $siteName;
    private string $siteUrl;
    private string $primaryKeyword;
    private array $keywords;
    private string $language;
    private string $description;
    private array $socialLinks;

    /**
     * Constructor.
     *
     * @param string $siteName       The name of the site.
     * @param string $siteUrl        The base URL of the site.
     * @param string $primaryKeyword The main keyword associated with the site.
     * @param array  $keywords       Additional keywords.
     * @param string $language       The language code (e.g., 'zh-CN').
     * @param string $description    A longer description (optional).
     * @param array  $socialLinks    Associative array of social media links.
     */
    public function __construct(
        string $siteName,
        string $siteUrl,
        string $primaryKeyword,
        array $keywords = [],
        string $language = 'zh-CN',
        string $description = '',
        array $socialLinks = []
    ) {
        $this->siteName = $siteName;
        $this->siteUrl = rtrim($siteUrl, '/');
        $this->primaryKeyword = $primaryKeyword;
        $this->keywords = $keywords;
        $this->language = $language;
        $this->description = $description;
        $this->socialLinks = $socialLinks;
    }

    /**
     * Get the site name.
     *
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * Get the site URL.
     *
     * @return string
     */
    public function getSiteUrl(): string
    {
        return $this->siteUrl;
    }

    /**
     * Get the primary keyword.
     *
     * @return string
     */
    public function getPrimaryKeyword(): string
    {
        return $this->primaryKeyword;
    }

    /**
     * Get all keywords (including primary).
     *
     * @return array
     */
    public function getAllKeywords(): array
    {
        return array_merge([$this->primaryKeyword], $this->keywords);
    }

    /**
     * Generate a short description text based on site metadata.
     *
     * The description is built from the site name, primary keyword,
     * and a portion of the keywords list. It is intended to be
     * concise and human-readable.
     *
     * @param int $maxKeywords Maximum number of additional keywords to include.
     * @return string
     */
    public function generateShortDescription(int $maxKeywords = 3): string
    {
        $parts = [];

        // Base: site name and primary keyword
        $base = sprintf('%s - %s', $this->siteName, $this->primaryKeyword);
        $parts[] = $base;

        // Add some additional keywords if available
        if (!empty($this->keywords)) {
            $selected = array_slice($this->keywords, 0, $maxKeywords);
            $keywordStr = implode(', ', $selected);
            $parts[] = $keywordStr;
        }

        // Add language info
        $parts[] = sprintf('语言: %s', $this->language);

        // Final description string, joined by " | "
        return implode(' | ', $parts);
    }

    /**
     * Generate a meta description suitable for HTML <meta> tag.
     * HTML special characters are escaped.
     *
     * @param int $maxLength Maximum length of the description.
     * @return string
     */
    public function generateMetaDescription(int $maxLength = 160): string
    {
        $raw = $this->generateShortDescription();
        if (mb_strlen($raw) > $maxLength) {
            $raw = mb_substr($raw, 0, $maxLength - 3) . '...';
        }
        return htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Get the social links array.
     *
     * @return array
     */
    public function getSocialLinks(): array
    {
        return $this->socialLinks;
    }

    /**
     * Add a social link.
     *
     * @param string $platform The platform name (e.g., 'twitter').
     * @param string $url      The full URL.
     * @return void
     */
    public function addSocialLink(string $platform, string $url): void
    {
        $this->socialLinks[$platform] = $url;
    }

    /**
     * Export metadata as an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'site_name'       => $this->siteName,
            'site_url'        => $this->siteUrl,
            'primary_keyword' => $this->primaryKeyword,
            'keywords'        => $this->keywords,
            'language'        => $this->language,
            'description'     => $this->description,
            'social_links'    => $this->socialLinks,
        ];
    }
}

// --- Example usage (can be removed or kept as a demonstration) ---

// Create a SiteMeta instance with real-world data
$meta = new SiteMeta(
    '乐鱼体育门户',
    'https://portal-h5-leyu.com.cn',
    '乐鱼体育',
    ['体育赛事', '在线投注', '电竞', '真人娱乐', '棋牌游戏'],
    'zh-CN',
    '乐鱼体育提供丰富的体育赛事和娱乐项目，致力于为用户打造一流的在线体验。',
    [
        'facebook' => 'https://facebook.com/leyusports',
        'twitter'  => 'https://twitter.com/leyusports',
    ]
);

// Generate and output a short description
echo $meta->generateShortDescription() . "\n";

// Generate an HTML-safe meta description
echo $meta->generateMetaDescription() . "\n";