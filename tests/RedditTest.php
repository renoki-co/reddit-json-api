<?php

namespace Rennokki\RedditAPI\Test;

use Rennokki\RedditAPI\RedditAPI;

class RedditTest extends TestCase
{
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->api = (new RedditAPI())->platform('web')
                                      ->appId('reddit-json-api')
                                      ->version('1.0.0')
                                      ->redditUsername('renodeer')
                                      ->subreddit('funny');
    }

    public function testNoInitialization()
    {
        $api = (new RedditAPI())->subreddit('funny');

        $this->assertFalse($api->isInitialized());
    }

    public function testInitialization()
    {
        $this->assertEquals($this->api->platform, 'web');
        $this->assertEquals($this->api->appId, 'reddit-json-api');
        $this->assertEquals($this->api->version, '1.0.0');
        $this->assertEquals($this->api->subreddit, 'funny');
    }

    public function testSubreddit()
    {
        $this->assertEquals($this->api->subreddit, 'funny');
        $this->assertContains('funny.json', $this->api->buildUrl());
    }

    public function testLimit()
    {
        $api = $this->api->limit(100);

        $this->assertEquals($api->limit, 100);
        $this->assertContains('limit=100', $api->buildUrl());
    }

    public function testAfter()
    {
        $api = $this->api->after('123');

        $this->assertEquals($api->after, '123');
        $this->assertContains('after=123', $api->buildUrl());
    }

    public function testBefore()
    {
        $api = $this->api->before('111');

        $this->assertEquals($api->before, '111');
        $this->assertContains('before=111', $api->buildUrl());
    }

    public function testGoodSort()
    {
        foreach (RedditAPI::$sorts as $sort) {
            $api = $this->api->sort($sort);

            $this->assertEquals($api->sort, $sort);
            $this->assertContains($sort.'.json', $api->buildUrl());
        }

        foreach (RedditAPI::$sorts as $sort) {
            $api = $this->api->{$sort}();

            $this->assertEquals($api->sort, $sort);
            $this->assertContains($sort.'.json', $api->buildUrl());
        }
    }

    public function testBadSort()
    {
        $api = $this->api->sort('bad');

        $this->assertNull($api->sort);
        $this->assertNotContains('bad.json', $api->buildUrl());
    }

    public function testSortShowingTimeWithTopOrControversial()
    {
        foreach (RedditAPI::$sorts as $sort) {
            $api = $this->api->sort($sort);

            if (in_array($sort, ['top', 'controversial'])) {
                $this->assertContains('?t='.$api->time, $api->buildUrl());
            } else {
                $this->assertNotContains('?t='.$api->time, $api->buildUrl());
            }
        }
    }

    public function testGoodTime()
    {
        $api = $this->api->controversial();

        foreach (RedditAPI::$times as $time) {
            $api = $this->api->time($time);

            $this->assertEquals($api->time, $time);
            $this->assertContains('?t='.$time, $api->buildUrl());
        }

        foreach (RedditAPI::$times as $time) {
            $api = $this->api->{$time}();

            $this->assertEquals($api->time, $time);
            $this->assertContains('?t='.$time, $api->buildUrl());
        }

        $api = $this->api->top();

        foreach (RedditAPI::$times as $time) {
            $api = $this->api->time($time);

            $this->assertEquals($api->time, $time);
            $this->assertContains('?t='.$time, $api->buildUrl());
        }

        foreach (RedditAPI::$times as $time) {
            $api = $this->api->{$time}();

            $this->assertEquals($api->time, $time);
            $this->assertContains('?t='.$time, $api->buildUrl());
        }
    }

    public function testBadTime()
    {
        $api = $this->api->controversial()->time('bad');

        $this->assertEquals($api->time, 'all');
        $this->assertNotContains('?t=bad', $api->buildUrl());
        $this->assertContains('?t=all', $api->buildUrl());

        $api = $this->api->top()->time('bad');

        $this->assertEquals($api->time, 'all');
        $this->assertNotContains('?t=bad', $api->buildUrl());
        $this->assertContains('?t=all', $api->buildUrl());
    }

    public function testClears()
    {
        $this->assertEquals($this->api->time('all')->clearTime()->time, 'all');
        $this->assertNull($this->api->sort('top')->clearSort()->sort);
        $this->assertNull($this->api->after('123')->clearAfter()->after);
        $this->assertNull($this->api->before('123')->clearBefore()->before);
    }

    public function testNoInitializationRequest()
    {
        try {
            (new RedditAPI())->subreddit('funny')->get();
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testHot()
    {
        try {
            $data = $this->api->hot()->get();
        } catch (\Exception $e) {
            return $this->assertFalse(true);
        }

        $this->assertTrue(count($data->data->children) > 1);
    }

    public function testTop()
    {
        try {
            $data = $this->api->top()->all()->get();
        } catch (\Exception $e) {
            return $this->assertFalse(true);
        }

        $this->assertTrue(count($data->data->children) > 1);
    }

    public function testNew()
    {
        try {
            $data = $this->api->new()->get();
        } catch (\Exception $e) {
            return $this->assertFalse(true);
        }

        $this->assertTrue(count($data->data->children) > 1);
    }
}
