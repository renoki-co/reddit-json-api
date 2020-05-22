[![Build Status](https://travis-ci.org/rennokki/reddit-json-api.svg?branch=master)](https://travis-ci.org/rennokki/reddit-json-api)
[![codecov](https://codecov.io/gh/rennokki/reddit-json-api/branch/master/graph/badge.svg)](https://codecov.io/gh/rennokki/reddit-json-api/branch/master)
[![StyleCI](https://github.styleci.io/repos/166289783/shield?branch=master)](https://github.styleci.io/repos/166289783)
[![Latest Stable Version](https://poser.pugx.org/rennokki/reddit-json-api/v/stable)](https://packagist.org/packages/rennokki/reddit-json-api)
[![Total Downloads](https://poser.pugx.org/rennokki/reddit-json-api/downloads)](https://packagist.org/packages/rennokki/reddit-json-api)
[![Monthly Downloads](https://poser.pugx.org/rennokki/reddit-json-api/d/monthly)](https://packagist.org/packages/rennokki/reddit-json-api)
[![License](https://poser.pugx.org/rennokki/reddit-json-api/license)](https://packagist.org/packages/rennokki/reddit-json-api)

[![PayPal](https://img.shields.io/badge/PayPal-donate-blue.svg)](https://paypal.me/rennokki)

# Reddit JSON API
Reddit JSON API is a simple request builder which helps you retrieve data from Reddit's JSON API. You can query subreddit for posts so far, so go ahead parsing posts!

# Installation
Install the package:
```bash
$ composer require rennokki/reddit-json-api
```

If you are using Laravel and your Laravel version does not support package discovery, add this line in the `providers` array in your `config/app.php` file:
```php
Rennokki\RedditApi\RedditApiServiceProvider::class,
```

# Setting up the API
This kind of API does not need credentials. The only things you need is to specify an User Agent for your requests. This can be done easy:
```php
use Rennokki\RedditApi\RedditApi;

$api = (new RedditApi())->platform('web')
                        ->appId('my-awesome-app')
                        ->version('1.0.0-beta')
                        ->redditUsername('myRedditUsername');

$dankPosts = $api->subreddit('dankmemes')->hot()->get();

foreach ($dankPosts->data->children as $post) {
  //
}
```

# Available methods
You can sort posts, you can specify a time for `controversial` or `top` sorts, you can query for `after`, `before` and even constrain a limit:

Specify a `limit`:
```php
$posts = $api->subreddit('memes')->limit(100)->get();
```

Specify a `before` or an `after`:
```php
$afterPosts = $api->subreddit('memes')->after('id_here')->get();
$beforePosts = $api->subreddit('memes')->before('id_here')->get();
```

Sort by importance:
```php
$hotPosts = $api->subreddit('memes')->hot()->get();
```

Alternatively, you can sort like this:
```php
$hotPosts = $api->subreddit('memes')->sort('hot')->get();
```

The remaining methods are the following, with their `sort()` alternative:
```php
->new()
->rising()
->controversial()
->top()

->sort('new')
->sort('rising')
->sort('controversial')
->sort('top')
```

The last two, `controversial` and `top` allow to specify a `time`:
```php
$allTimeTopPosts = $api->subreddit('memes')->top()->all()->get();
```

Alternatively, exactly like the `sort()` function, there's the `time()` function that allows you to input a string. All methods to sort the time are the following:
```php
->hour()
->day()
->week()
->month()
->year()

->time('hour')
->time('day')
->time('week')
->time('month')
->time('year')
```

If no time is specified, the default one is `all()`.

Since the class is a builder, you might want to clear time, sort, before or after. You can do so:
```php
$api->clearBefore()->...;
$api->clearAfter()->...;
$api->clearSort()->...;
$api->clearTime()->...; // this reverts to default: 'all'
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email alex@renoki.org instead of using the issue tracker.

## Credits

- [Alex Renoki](https://github.com/rennokki)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
