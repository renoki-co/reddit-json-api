<?php

namespace Rennokki\RedditApi;

use Exception;

class App
{
    /**
     * The platform that will be passed
     * to the User Agent.
     *
     * @see https://github.com/reddit-archive/reddit/wiki/API#rules
     * @var mixed
     */
    protected $platform;

    /**
     * The app version that will be passed
     * to the User Agent.
     *
     * @see https://github.com/reddit-archive/reddit/wiki/API#rules
     * @var mixed
     */
    protected $appId;

    /**
     * The version that will be passed
     * to the User Agent.
     *
     * @see https://github.com/reddit-archive/reddit/wiki/API#rules
     * @var mixed
     */
    protected $version;

    /**
     * The reddit username, without
     * the prefixed by /u/.
     *
     * @var string|null
     */
    protected $redditUsername;

    /**
     * Initialize a new class.
     *
     * @param  string  $appId
     * @param  string  $version
     * @param  string  $platform
     * @param  string  $redditUsername
     * @return void
     */
    public function __construct(string $appId, string $version, string $platform, string $redditUsername)
    {
        $this->appId = $appId;
        $this->version = $version;
        $this->platform = $platform;
        $this->redditUsername = $redditUsername;
    }

    /**
     * Check if the API was initialized.
     *
     * @return bool
     */
    public function isInitialized(): bool
    {
        return
            $this->platform &&
            $this->appId &&
            $this->version &&
            $this->redditUsername;
    }

    /**
     * Get the user agent for the requests.
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        if (! $this->isInitialized()) {
            throw new Exception('You have not initalized properly the app.');
        }

        return "{$this->platform}:{$this->appId}:{$this->version} (by /u/{$this->redditUsername})";
    }

    /**
     * Flush the current app instance.
     *
     * @return void
     */
    public function flush()
    {
        unset($this->appId, $this->version, $this->platform, $this->redditUsername);
    }
}
