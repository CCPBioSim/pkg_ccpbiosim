<?php
namespace Ccpbiosim\Module\Youtube\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Cache\Controller\CallbackController;
use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Log\Log;
use Joomla\Registry\Registry;

class YoutubeHelper
{
    private const API_SEARCH_URL = 'https://www.googleapis.com/youtube/v3/search';
    private const API_VIDEOS_URL = 'https://www.googleapis.com/youtube/v3/videos';

    public static function getVideos(Registry $params, CMSApplicationInterface $app): array
    {
        $apiKey     = trim($params->get('api_key', ''));
        $channelId  = trim($params->get('channel_id', ''));
        $maxResults = (int) $params->get('video_count', 6);
        $cacheTime  = (int) $params->get('cache_time', 3600);

        if (empty($apiKey) || empty($channelId)) {
            return [];
        }

        if ($cacheTime > 0) {
            $cache = Factory::getContainer()
                ->get(CacheControllerFactoryInterface::class)
                ->createCacheController('callback', [
                    'defaultgroup' => 'mod_ccpbiosim_youtube',
                    'lifetime'     => (int) ($cacheTime / 60), // Joomla cache uses minutes
                    'caching'      => true,
                ]);

            $cacheKey = md5($channelId . $maxResults);

            return $cache->get(
                function () use ($apiKey, $channelId, $maxResults) {
                    return self::fetchFromApi($apiKey, $channelId, $maxResults);
                },
                [],
                $cacheKey
            ) ?: [];
        }

        return self::fetchFromApi($apiKey, $channelId, $maxResults);
    }

    private static function fetchFromApi(string $apiKey, string $channelId, int $maxResults): array
    {
        try {
            $http = HttpFactory::getHttp();

            $searchParams = http_build_query([
                'key'        => $apiKey,
                'channelId'  => $channelId,
                'part'       => 'id',
                'order'      => 'date',
                'type'       => 'video',
                'maxResults' => min($maxResults, 50),
            ]);

            $searchResponse = $http->get(self::API_SEARCH_URL . '?' . $searchParams, [], 10);

            if ($searchResponse->code !== 200) {
                Log::add(
                    'mod_ccpbiosim_youtube: YouTube search API returned ' . $searchResponse->code,
                    Log::WARNING,
                    'mod_ccpbiosim_youtube'
                );
                return [];
            }

            $searchData = json_decode($searchResponse->body, false, 512, JSON_THROW_ON_ERROR);
            $videoIds   = array_map(
                static fn($item) => $item->id->videoId,
                $searchData->items ?? []
            );

            if (empty($videoIds)) {
                return [];
            }

            $videosParams = http_build_query([
                'key'  => $apiKey,
                'id'   => implode(',', $videoIds),
                'part' => 'snippet,statistics',
            ]);

            $videosResponse = $http->get(self::API_VIDEOS_URL . '?' . $videosParams, [], 10);

            if ($videosResponse->code !== 200) {
                Log::add(
                    'mod_ccpbiosim_youtube: YouTube videos API returned ' . $videosResponse->code,
                    Log::WARNING,
                    'mod_ccpbiosim_youtube'
                );
                return [];
            }

            $videosData = json_decode($videosResponse->body, false, 512, JSON_THROW_ON_ERROR);

            return array_map(
                static function ($item) {
                    $snippet    = $item->snippet;
                    $thumbs     = $snippet->thumbnails;

                    $thumbnail = $thumbs->maxres->url
                        ?? $thumbs->high->url
                        ?? $thumbs->medium->url
                        ?? $thumbs->default->url
                        ?? '';

                    return (object) [
                        'id'           => $item->id,
                        'title'        => $snippet->title,
                        'description'  => $snippet->description,
                        'thumbnail'    => $thumbnail,
                        'channelTitle' => $snippet->channelTitle,
                        'publishedAt'  => $snippet->publishedAt,
                        'url'          => 'https://www.youtube.com/watch?v=' . $item->id,
                        'embedUrl'     => 'https://www.youtube.com/embed/' . $item->id,
                        'viewCount'    => $item->statistics->viewCount ?? 0,
                    ];
                },
                $videosData->items ?? []
            );

        } catch (\Throwable $e) {
            Log::add(
                'mod_ccpbiosim_youtube exception: ' . $e->getMessage(),
                Log::ERROR,
                'mod_ccpbiosim_youtube'
            );
            return [];
        }
    }
}
