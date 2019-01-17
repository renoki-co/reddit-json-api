<?php

namespace Rennokki\RedditAPI;

use GuzzleHttp\Client as GuzzleClient;

class RedditAPI
{
    public $client;
    public $platform;
    public $appId;
    public $version;
    public $redditUsername;

    public $after;
    public $before;
    public $limit = 20;

    public $subreddit;
    public $sort;
    public $time = 'all';

    public static $sorts = [
        'hot', 'new', 'controversial', 'top', 'rising',
    ];

    public static $times = [
        'hour', 'day', 'week', 'month', 'year', 'all',
    ];

    public static $redditUrl = 'https://reddit.com';

    public function __construct()
    {
        $this->client = new GuzzleClient();
    }

    public function platform(string $platform)
    {
        $this->platform = $platform;

        return $this;
    }

    public function appId(string $appId)
    {
        $this->appId = $appId;

        return $this;
    }

    public function version(string $version)
    {
        $this->version = $version;

        return $this;
    }

    public function redditUsername(string $redditUsername)
    {
        $this->redditUsername = $redditUsername;

        return $this;
    }

    public function after(string $after)
    {
        $this->after = $after;

        return $this;
    }

    public function before(string $before)
    {
        $this->before = $before;

        return $this;
    }

    public function limit(int $limit = 20)
    {
        $this->limit = $limit;

        return $this;
    }

    public function subreddit(string $subreddit)
    {
        $this->subreddit = $subreddit;

        return $this;
    }

    public function sort(string $sort)
    {
        if (in_array($sort, self::$sorts)) {
            $this->sort = $sort;
        }

        return $this;
    }

    public function time(string $time)
    {
        if (in_array($time, self::$times)) {
            $this->time = $time;
        }

        return $this;
    }

    public function hot()
    {
        return $this->sort('hot');
    }

    public function new()
    {
        return $this->sort('new');
    }

    public function controversial()
    {
        return $this->sort('controversial');
    }

    public function top()
    {
        return $this->sort('top');
    }

    public function rising()
    {
        return $this->sort('rising');
    }

    public function hour()
    {
        return $this->time('hour');
    }

    public function day()
    {
        return $this->time('day');
    }

    public function week()
    {
        return $this->time('week');
    }

    public function month()
    {
        return $this->time('month');
    }

    public function year()
    {
        return $this->time('year');
    }

    public function all()
    {
        return $this->time('all');
    }

    public function clearTime()
    {
        $this->time = 'all';

        return $this;
    }

    public function clearSort()
    {
        $this->sort = null;

        return $this;
    }

    public function clearAfter()
    {
        $this->after = null;

        return $this;
    }

    public function clearBefore()
    {
        $this->before = null;

        return $this;
    }

    public function get()
    {
        if (! $this->isInitialized()) {
            throw new \Exception('You have not initalized properly the app.');
        }

        try {
            $request = $this->client->request('GET', $this->buildUrl(), [
                'headers' => [
                    'Accepts' => 'application/json',
                    'User-Agent' => $this->platform.':'.$this->appId.':'.$this->version.' (by /u/'.$this->redditUsername.')',
                ],
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }

        return json_decode($request->getBody());
    }

    public function isInitialized(): bool
    {
        return $this->platform && $this->appId && $this->version && $this->redditUsername && $this->subreddit;
    }

    public function buildUrl(): string
    {
        $queryParameters = [];
        $url = self::$redditUrl.'/r/'.$this->subreddit;

        if ($this->sort) {
            $url .= '/'.$this->sort;
        }

        $url .= '.json';

        if (in_array($this->sort, ['top', 'controversial'])) {
            $queryParameters['t'] = $this->time;
        }

        if ($this->limit) {
            $queryParameters['limit'] = $this->limit;
        }

        if ($this->after) {
            $queryParameters['after'] = $this->after;
        }

        if ($this->before) {
            $queryParameters['before'] = $this->before;
        }

        return $url.(($queryParameters) ? '?'.http_build_query($queryParameters) : null);
    }
}
