<?php

namespace Rennokki\RedditApi\Test;

use Rennokki\RedditApi\Reddit;
use  Rennokki\RedditApi\RedditList;
use Rennokki\RedditApi\Subreddit;

class RedditTest extends TestCase
{
    /**
     * The API instance.
     *
     * @var \Rennokki\RedditApi\Subreddit
     */
    protected $subreddit;

    /**
     * The App instance.
     *
     * @var \Rennokki\RedditApi\App
     */
    protected $app;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app = Reddit::app(
            'renoki-co/reddit-json-api',
            '2.0',
            'web',
            'someusername'
        );

        $this->api = Reddit::subreddit('funny', $this->app);
    }

    public function test_subreddit_json_name()
    {
        $this->assertStringContainsString(
            'funny.json', $this->api->getCallableUrl()
        );
    }

    public function test_subreddit_limit()
    {
        $this->api->setLimit(100);

        $this->assertStringContainsString(
            'limit=100', $this->api->getCallableUrl()
        );
    }

    public function test_after()
    {
        $this->api->after('123');

        $this->assertStringContainsString(
            'after=123', $this->api->getCallableUrl()
        );
    }

    public function test_sort_filtering()
    {
        foreach (Subreddit::$sorts as $sort) {
            $this->api->sort($sort);

            $this->assertStringContainsString(
                "{$sort}.json", $this->api->getCallableUrl()
            );
        }

        $this->api->sort('bad');

        $this->assertStringNotContainsString(
            'bad.json', $this->api->getCallableUrl()
        );
    }

    public function test_time_filtering()
    {
        $this->api->sort('controversial');

        foreach (Subreddit::$times as $time) {
            $this->api->time($time);

            $this->assertStringContainsString(
                "?t={$time}", $this->api->getCallableUrl()
            );
        }

        $this->api->sort('top');

        foreach (Subreddit::$times as $time) {
            $this->api->time($time);

            $this->assertStringContainsString(
                "?t={$time}", $this->api->getCallableUrl()
            );
        }

        $this
            ->api
            ->sort('controversial')
            ->time('bad');

        $this->assertStringNotContainsString(
            '?t=bad', $this->api->getCallableUrl()
        );

        $this->assertStringContainsString(
            '?t=all', $this->api->getCallableUrl()
        );
    }

    public function test_top()
    {
        $posts = $this
            ->api
            ->sort('hot')
            ->get();

        $this->assertInstanceOf(RedditList::class, $posts);

        $this->assertCount(22, $posts);

        foreach ($posts as $post) {
            $this->assertNotNull($post['permalink'] ?? null);
        }
    }

    public function test_hot()
    {
        $posts = $this
            ->api
            ->sort('top')
            ->time('all')
            ->get();

        $this->assertInstanceOf(RedditList::class, $posts);

        $this->assertCount(20, $posts);

        foreach ($posts as $post) {
            $this->assertNotNull($post['permalink'] ?? null);
        }
    }

    public function test_new()
    {
        $posts = $this
            ->api
            ->sort('new')
            ->get();

        $this->assertInstanceOf(RedditList::class, $posts);

        $this->assertCount(20, $posts);

        foreach ($posts as $post) {
            $this->assertNotNull($post['permalink'] ?? null);
        }
    }

    public function test_next_page()
    {
        $posts = $this
            ->api
            ->sort('new')
            ->get();

        $firstPageAfter = $posts->getAfter();

        $posts = $posts->nextPage();

        $secondPageAfter = $posts->getAfter();

        $this->assertNotEquals($firstPageAfter, $secondPageAfter);
    }
}
