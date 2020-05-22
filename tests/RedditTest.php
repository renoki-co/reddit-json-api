<?php

namespace Rennokki\RedditApi\Test;

use Exception;
use Rennokki\RedditApi\Reddit;
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

    public function test_before()
    {
        $this->api->before('111');

        $this->assertStringContainsString(
            'before=111', $this->api->getCallableUrl()
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
        try {
            $data = $this
                ->api
                ->sort('hot')
                ->get();
        } catch (Exception $e) {
            return $this->assertFalse(true);
        }

        $this->assertTrue(
            count($data['data']['children']) > 1
        );
    }

    public function test_hot()
    {
        try {
            $data = $this
                ->api
                ->sort('top')
                ->time('all')
                ->get();
        } catch (Exception $e) {
            return $this->assertFalse(true);
        }

        $this->assertTrue(
            count($data['data']['children']) > 1
        );
    }

    public function test_new()
    {
        try {
            $data = $this
                ->api
                ->sort('new')
                ->get();
        } catch (Exception $e) {
            return $this->assertFalse(true);
        }

        $this->assertTrue(
            count($data['data']['children']) > 1
        );
    }
}
