<?php

namespace Rennokki\RedditApi;

class Reddit
{
    /**
     * Create a new Subreddit instance.
     *
     * @param  string  $subreddit
     * @param  \Rennokki\RedditApi\App  $app
     * @return \Rennokki\RedditApi\Subreddit
     */
    public static function subreddit(string $subreddit, App $app)
    {
        return new Subreddit($subreddit, $app);
    }

    /**
     * Initialize a new app class.
     *
     * @param  string  $appId
     * @param  string  $version
     * @param  string  $platform
     * @param  string  $redditUsername
     * @return \Rennokki\RedditApi\App
     */
    public static function app(string $appId, string $version, string $platform, string $redditUsername)
    {
        return new App($appId, $version, $platform, $redditUsername);
    }
}
