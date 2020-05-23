<?php

namespace Rennokki\RedditApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Subreddit
{
    /**
     * The app instance.
     *
     * @var \Rennokki\RedditApi\App
     */
    protected $app;

    /**
     * The `after` token for pagination.
     *
     * @return string|null
     */
    protected $after = null;

    /**
     * The limit on each page.
     * An `after` is passed by the API
     * to retrieve the next page.
     *
     * @var int
     */
    protected $limit = 20;

    /**
     * The sortation for posts or comments.
     *
     * @var null|string
     */
    protected $sort;

    /**
     * The time of filtering.
     *
     * @var string
     */
    protected $time = 'all';

    /**
     * The only available sorts.
     *
     * @var array
     */
    public static $sorts = [
        'hot', 'new', 'controversial', 'top', 'rising',
    ];

    /**
     * The only available times.
     *
     * @var array
     */
    public static $times = [
        'hour', 'day', 'week', 'month', 'year', 'all',
    ];

    /**
     * Initialize a new class.
     *
     * @param  string  $subreddit
     * @return void
     */
    public function __construct(string $subreddit, App $app)
    {
        $this->app = $app;
        $this->subreddit = $subreddit;
    }

    /**
     * Set the after token.
     *
     * @param  string|null  $after
     * @return $this
     */
    public function after(string $after)
    {
        $this->after = $after;

        return $this;
    }

    /**
     * Set the limit of posts.
     *
     * @param  int  $limit
     * @return $this
     */
    public function setLimit(int $limit = 20)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Set the sorting method.
     *
     * @param  string  $sort
     * @return $this
     */
    public function sort(string $sort)
    {
        if (in_array($sort, static::$sorts)) {
            $this->sort = $sort;
        }

        return $this;
    }

    /**
     * Set the time for the filters.
     *
     * @param  string  $time
     * @return $this
     */
    public function time(string $time)
    {
        if (in_array($time, static::$times)) {
            $this->time = $time;
        }

        return $this;
    }

    /**
     * Call the API to get the response as array.
     *
     * @return array|null
     */
    public function get()
    {
        $client = new Client;

        try {
            $request = $client->request('GET', $this->getCallableUrl(), [
                'headers' => [
                    'Accepts' => 'application/json',
                    'User-Agent' => $this->app->getUserAgent(),
                ],
            ]);
        } catch (ClientException $e) {
            return json_decode(
                $e->getResponse()->getBody()->getContents(),
                true
            );
        }

        return @json_decode($request->getBody(), true);
    }

    /**
     * Get the callable URL for the subreddit.
     *
     * @return string
     */
    public function getCallableUrl(): string
    {
        $parameters = [];

        $url = "https://reddit.com/r/{$this->subreddit}";

        if ($this->sort) {
            $url .= "/{$this->sort}";
        }

        if (in_array($this->sort, ['top', 'controversial'])) {
            $parameters['t'] = $this->time;
        }

        if ($this->limit) {
            $parameters['limit'] = $this->limit;
        }

        if ($this->after) {
            $parameters['after'] = $this->after;
        }

        $query = http_build_query($parameters);

        return "{$url}.json?{$query}";
    }
}
