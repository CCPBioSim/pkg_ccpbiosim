<?php

/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Site\Model\Statistics;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Uri\Uri;

/**
 * Statistics Model
 *
 * Provides all data for the statistics view:
 *   - Event counts and attendance broken down by category and year
 *   - Training course (container) counts from assets.json
 *   - Software package details (with GitHub metrics) from assets.json
 */
class StatisticsModel extends BaseDatabaseModel
{
    /**
     * Remote URL for the assets JSON file.
     */
    private const ASSETS_JSON_URL = 'https://ccpbiosim.github.io/assets.json';

    /**
     * GitHub API base URL.
     */
    private const GITHUB_API_BASE = 'https://api.github.com/repos/';

    /**
     * How long (seconds) to cache remote responses.
     * 3600 = 1 hour.
     */
    private const CACHE_TTL = 3600;

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Returns the complete data payload consumed by the tmpl layout.
     *
     * @return array{
     *   events: array,
     *   containers: array,
     *   software: array
     * }
     */
    public function getStatisticsData(): array
    {
        return [
            'events'     => $this->getEventStatistics(),
            'containers' => $this->getContainerStatistics(),
            'software'   => $this->getSoftwareStatistics(),
        ];
    }

    // -------------------------------------------------------------------------
    // Event statistics (database)
    // -------------------------------------------------------------------------

    /**
     * Fetches and aggregates all event statistics from the database.
     *
     * @return array{
     *   allTime: array,
     *   byCategory: array,
     *   byYear: array,
     *   years: array,
     *   categories: array
     * }
     */
    private function getEventStatistics(): array
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        // Join events with their categories, only published events (state = 1)
        $query->select([
            $db->quoteName('e.id'),
            $db->quoteName('e.attendance'),
            $db->quoteName('e.startdatetime'),
            $db->quoteName('c.id',            'category_id'),
            $db->quoteName('c.eventcategory', 'category_name'),
        ])
        ->from($db->quoteName('#__ccpbiosim_events', 'e'))
        ->join(
            'LEFT',
            $db->quoteName('#__ccpbiosim_event_categories', 'c')
                . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('e.category')
        )
        ->where($db->quoteName('e.state') . ' = 1');

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        // ---- Initialise accumulators ----------------------------------------
        $allTimeCount      = 0;
        $allTimeAttendance = 0;

        $byCategory = [];   // category_id => [name, count, attendance]
        $byYear     = [];   // year => category_id => [count, attendance]
        $years      = [];
        $categories = [];   // category_id => name

        foreach ($rows as $row) {
            $catId   = (int) $row->category_id;
            $catName = $row->category_name ?? 'Uncategorised';
            $att     = (float) ($row->attendance ?? 0);
            $year    = $row->startdatetime
                ? (int) date('Y', strtotime($row->startdatetime))
                : null;

            // All-time totals
            $allTimeCount++;
            $allTimeAttendance += $att;

            // By category
            if (!isset($byCategory[$catId])) {
                $byCategory[$catId] = [
                    'name'       => $catName,
                    'count'      => 0,
                    'attendance' => 0.0,
                ];
            }
            $byCategory[$catId]['count']++;
            $byCategory[$catId]['attendance'] += $att;

            // By year
            if ($year !== null) {
                $years[$year] = true;
                if (!isset($byYear[$year][$catId])) {
                    $byYear[$year][$catId] = ['count' => 0, 'attendance' => 0.0];
                }
                $byYear[$year][$catId]['count']++;
                $byYear[$year][$catId]['attendance'] += $att;
            }

            $categories[$catId] = $catName;
        }

        ksort($byYear);
        $years = array_keys($years);
        sort($years);

        return [
            'allTime'    => [
                'count'      => $allTimeCount,
                'attendance' => $allTimeAttendance,
            ],
            'byCategory' => $byCategory,
            'byYear'     => $byYear,
            'years'      => $years,
            'categories' => $categories,
        ];
    }

    // -------------------------------------------------------------------------
    // Assets JSON (containers + software)
    // -------------------------------------------------------------------------

    /**
     * Fetches and parses the remote assets.json file.
     * Results are cached in the Joomla filesystem cache.
     *
     * @return array{containers: array, software: array}
     */
    private function fetchAssetsJson(): array
    {
        $cacheKey  = 'ccpbiosim_assets_json';
        $cacheFile = JPATH_CACHE . '/ccpbiosim/' . $cacheKey . '.json';

        // Serve from cache when still fresh
        if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < self::CACHE_TTL) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        // Fetch remotely
        $http     = Factory::getContainer()->get(\Joomla\CMS\Http\HttpFactory::class)->getHttp();
        $response = $http->get(self::ASSETS_JSON_URL, [], 10);

        if ($response->code !== 200) {
            return ['containers' => [], 'software' => []];
        }

        $data = json_decode($response->body, true);
        if (!is_array($data)) {
            return ['containers' => [], 'software' => []];
        }

        // Persist to cache
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($cacheFile, json_encode($data));

        return $data;
    }

    /**
     * Counts training course containers grouped by category.
     *
     * @return array{
     *   total: int,
     *   byCategory: array<string, int>
     * }
     */
    private function getContainerStatistics(): array
    {
        $assets     = $this->fetchAssetsJson();
        $containers = $assets['containers'] ?? [];

        $byCategory = [];
        foreach ($containers as $key => $container) {
            $cat = $container['category'] ?? 'uncategorised';
            $byCategory[$cat] = ($byCategory[$cat] ?? 0) + 1;
        }

        return [
            'total'      => count($containers),
            'byCategory' => $byCategory,
        ];
    }

    /**
     * Retrieves software package data and enriches each with live GitHub metrics.
     *
     * @return list<array{
     *   key: string,
     *   name: string,
     *   shortdesc: string,
     *   github: string,
     *   docs: string,
     *   pypi: bool,
     *   conda: bool,
     *   category: string,
     *   github_metrics: array
     * }>
     */
    private function getSoftwareStatistics(): array
    {
        $assets   = $this->fetchAssetsJson();
        $packages = $assets['software'] ?? [];

        $result = [];
        foreach ($packages as $key => $pkg) {
            $metrics = [];

            if (!empty($pkg['github'])) {
                $metrics = $this->fetchGitHubMetrics($pkg['github']);
            }

            $result[] = [
                'key'            => $key,
                'name'           => $pkg['name']      ?? $key,
                'shortdesc'      => $pkg['shortdesc']  ?? '',
                'github'         => $pkg['github']     ?? '',
                'docs'           => $pkg['docs']       ?? '',
                'pypi'           => (bool) ($pkg['pypi']  ?? false),
                'conda'          => (bool) ($pkg['conda'] ?? false),
                'category'       => $pkg['category']   ?? '',
                'github_metrics' => $metrics,
            ];
        }

        return $result;
    }

    /**
     * Fetches repository metrics from the GitHub REST API.
     * Results are cached per repository.
     *
     * @param  string $githubUrl  Full GitHub repository URL, e.g. https://github.com/org/repo
     * @return array{
     *   stars: int,
     *   forks: int,
     *   open_issues: int,
     *   watchers: int,
     *   latest_release: string|null,
     *   latest_release_date: string|null,
     *   last_push: string|null,
     *   html_url: string
     * }
     */
    private function fetchGitHubMetrics(string $githubUrl): array
    {
        // Derive "owner/repo" from the URL
        $path = rtrim(parse_url($githubUrl, PHP_URL_PATH), '/');
        $parts = array_filter(explode('/', $path));
        if (count($parts) < 2) {
            return [];
        }
        $parts    = array_values($parts);
        $repoSlug = $parts[count($parts) - 2] . '/' . $parts[count($parts) - 1];

        $cacheKey  = 'github_' . str_replace('/', '_', $repoSlug);
        $cacheFile = JPATH_CACHE . '/ccpbiosim/' . $cacheKey . '.json';

        if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < self::CACHE_TTL) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        $apiUrl  = self::GITHUB_API_BASE . $repoSlug;
        $headers = [
            'User-Agent' => 'CCPBioSim-Joomla-Component/1.0',
            'Accept'     => 'application/vnd.github.v3+json',
        ];

        $http     = Factory::getContainer()->get(\Joomla\CMS\Http\HttpFactory::class)->getHttp();
        $response = $http->get($apiUrl, $headers, 10);

        if ($response->code !== 200) {
            return [];
        }

        $repo = json_decode($response->body, true);
        if (!is_array($repo)) {
            return [];
        }

        // Fetch latest release separately
        $latestRelease     = null;
        $latestReleaseDate = null;
        $relResponse       = $http->get($apiUrl . '/releases/latest', $headers, 10);
        if ($relResponse->code === 200) {
            $rel               = json_decode($relResponse->body, true);
            $latestRelease     = $rel['tag_name']     ?? null;
            $latestReleaseDate = $rel['published_at'] ?? null;
        }

        $metrics = [
            'stars'               => (int) ($repo['stargazers_count']  ?? 0),
            'forks'               => (int) ($repo['forks_count']        ?? 0),
            'open_issues'         => (int) ($repo['open_issues_count']  ?? 0),
            'watchers'            => (int) ($repo['watchers_count']     ?? 0),
            'latest_release'      => $latestRelease,
            'latest_release_date' => $latestReleaseDate,
            'last_push'           => $repo['pushed_at'] ?? null,
            'html_url'            => $repo['html_url']  ?? $githubUrl,
        ];

        // Persist
        file_put_contents($cacheFile, json_encode($metrics));

        return $metrics;
    }
}
