<?php

namespace Rennokki\RedditApi\Test;

use Rennokki\RedditApi\Reddit;

class AppTest extends TestCase
{
    public function test_app_not_initialized()
    {
        $app = Reddit::app('', '', '', '');

        $this->assertFalse($app->isInitialized());
    }

    public function test_app_user_agent()
    {
        $app = Reddit::app(
            'renoki-co/reddit-json-api',
            '2.0',
            'web',
            'someusername'
        );

        $this->assertEquals(
            'web:renoki-co/reddit-json-api:2.0 (by /u/someusername)',
            $app->getUserAgent()
        );
    }
}
