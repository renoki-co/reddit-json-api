<?php

namespace Rennokki\RedditApi;

use Illuminate\Support\Collection;

class RedditList extends Collection
{
    /**
     * The Subreddit instance from which
     * the content was filled.
     *
     * @var \Rennokki\RedditApi\Subreddit
     */
    protected $subreddit;

    /**
     * The `after` token for pagination.
     *
     * @return string|null
     */
    protected $after = null;

    /**
     * Set the Subreddit instance from which
     * the content was filled.
     *
     * @param  \Rennokki\RedditApi\Subreddit  $subreddit
     * @return $this
     */
    public function setSubreddit(Subreddit $subreddit)
    {
        $this->subreddit = $subreddit;

        return $this;
    }

    /**
     * Set the after token from the request.
     *
     * @param  string|null  $after
     * @return $this
     */
    public function setAfter($after)
    {
        $this->after = $after;

        return $this;
    }

    /**
     * Get the next page of results.
     *
     * @return \Rennokki\RedditApi\RedditList
     */
    public function nextPage()
    {
        return $this->subreddit
            ->after($this->getAfter())
            ->get();
    }

    /**
     * Get the after token.
     *
     * @return string|null
     */
    public function getAfter()
    {
        return $this->after;
    }
}
