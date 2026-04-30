<?php
/**
 * @package    com_ccpbiosim
 * @copyright  2025 CCPBioSim Team
 * @license    MIT
 */

namespace Ccpbiosim\Component\Ccpbiosim\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Statistics Model
 */
class StatisticsModel extends BaseDatabaseModel
{
    private const ASSETS_JSON_URL = 'https://ccpbiosim.github.io/assets.json';
    private const GITHUB_API_BASE = 'https://api.github.com/repos/';
    private const CACHE_TTL       = 3600;

    protected $_statisticsData = null;

    public function getStatisticsData(): array
    {
        if ($this->_statisticsData !== null) {
            return $this->_statisticsData;
        }

        $this->_statisticsData = [
            'events'     => $this->getEventStatistics(),
            'containers' => $this->getContainerStatistics(),
            'software'   => $this->getSoftwareStatistics(),
        ];

        return $this->_statisticsData;
    }

    private function getEventStatistics(): array
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

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

        $allTimeCount      = 0;
        $allTimeAttendance = 0;
        $byCategory        = [];
        $byYear            = [];
        $years             = [];
        $categories        = [];

        foreach ($rows as $row) {
            $catId   = (int) $row->category_id;
            $catName = $row->category_name ?? 'Uncategorised';
            $att     = (float) ($row->attendance ?? 0);
            $year    = $row->startdatetime
                ? (int) date('Y', strtotime($row->startdatetime))
                : null;

            $allTimeCount++;
            $allTimeAttendance += $att;

            if (!isset($byCategory[$catId])) {
                $byCategory[$catId] = ['name' => $catName, 'count' => 0, 'attendance' => 0.0];
            }
            $byCategory[$catId]['count']++;
            $byCategory[$catId]['attendance'] += $att;

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
            'allTime'    => ['count' => $allTimeCount, 'attendance' => $allTimeAttendance],
            'byCategory' => $byCategory,
            'byYear'     => $byYear,
            'years'      => $years,
            'categories' => $categories,
        ];
    }

    private function fetchAssetsJson(): array
    {
        $cacheFile = JPATH_CACHE . '/ccpbiosim/assets_json.json';

        if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < self::CACHE_TTL) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        $http     = HttpFactory::getHttp();
        $response = $http->get(self::ASSETS_JSON_URL, [], 10);

        if ($response->code !== 200) {
            return ['containers' => [], 'software' => []];
        }

        $data = json_decode($response->body, true);
        if (!is_array($data)) {
            return ['containers' => [], 'software' => []];
        }

        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($cacheFile, json_encode($data));

        return $data;
    }

    private function getContainerStatistics(): array
    {
        $assets     = $this->fetchAssetsJson();
        $containers = $assets['containers'] ?? [];

        $byCategory = [];
        foreach ($containers as $container) {
            $cat = $container['category'] ?? 'uncategorised';
            $byCategory[$cat] = ($byCategory[$cat] ?? 0) + 1;
        }

        return [
            'total'      => count($containers),
            'byCategory' => $byCategory,
        ];
    }

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
                'name'           => $pkg['name']     ?? $key,
                'shortdesc'      => $pkg['shortdesc'] ?? '',
                'github'         => $pkg['github']    ?? '',
                'docs'           => $pkg['docs']      ?? '',
                'pypi'           => (bool) ($pkg['pypi']  ?? false),
                'conda'          => (bool) ($pkg['conda'] ?? false),
                'category'       => $pkg['category']  ?? '',
                'github_metrics' => $metrics,
            ];
        }

        return $result;
    }

    private function fetchGitHubMetrics(string $githubUrl): array
    {
        $path  = rtrim(parse_url($githubUrl, PHP_URL_PATH), '/');
        $parts = array_values(array_filter(explode('/', $path)));

        if (count($parts) < 2) {
            return [];
        }

        $repoSlug  = $parts[count($parts) - 2] . '/' . $parts[count($parts) - 1];
        $cacheFile = JPATH_CACHE . '/ccpbiosim/github_' . str_replace('/', '_', $repoSlug) . '.json';

        if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < self::CACHE_TTL) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        $headers = [
            'User-Agent' => 'CCPBioSim-Joomla-Component/1.0',
            'Accept'     => 'application/vnd.github.v3+json',
        ];

        $http     = HttpFactory::getHttp();
        $apiUrl   = self::GITHUB_API_BASE . $repoSlug;
        $response = $http->get($apiUrl, $headers, 10);

        if ($response->code !== 200) {
            return [];
        }

        $repo = json_decode($response->body, true);
        if (!is_array($repo)) {
            return [];
        }

        $latestRelease     = null;
        $latestReleaseDate = null;
        $relResponse       = $http->get($apiUrl . '/releases/latest', $headers, 10);
        if ($relResponse->code === 200) {
            $rel               = json_decode($relResponse->body, true);
            $latestRelease     = $rel['tag_name']     ?? null;
            $latestReleaseDate = $rel['published_at'] ?? null;
        }

        $metrics = [
            'stars'               => (int) ($repo['stargazers_count'] ?? 0),
            'forks'               => (int) ($repo['forks_count']       ?? 0),
            'open_issues'         => (int) ($repo['open_issues_count'] ?? 0),
            'watchers'            => (int) ($repo['watchers_count']    ?? 0),
            'latest_release'      => $latestRelease,
            'latest_release_date' => $latestReleaseDate,
            'last_push'           => $repo['pushed_at'] ?? null,
            'html_url'            => $repo['html_url']  ?? $githubUrl,
        ];

        file_put_contents($cacheFile, json_encode($metrics));

        return $metrics;
    }
}
?>
