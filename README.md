![CI](https://github.com/renoki-co/reddit-json-api/workflows/CI/badge.svg?branch=master)
[![codecov](https://codecov.io/gh/renoki-co/reddit-json-api/branch/master/graph/badge.svg)](https://codecov.io/gh/renoki-co/reddit-json-api/branch/master)
[![StyleCI](https://github.styleci.io/repos/166289783/shield?branch=master)](https://github.styleci.io/repos/166289783)
[![Latest Stable Version](https://poser.pugx.org/rennokki/reddit-json-api/v/stable)](https://packagist.org/packages/rennokki/reddit-json-api)
[![Total Downloads](https://poser.pugx.org/rennokki/reddit-json-api/downloads)](https://packagist.org/packages/rennokki/reddit-json-api)
[![Monthly Downloads](https://poser.pugx.org/rennokki/reddit-json-api/d/monthly)](https://packagist.org/packages/rennokki/reddit-json-api)
[![License](https://poser.pugx.org/rennokki/reddit-json-api/license)](https://packagist.org/packages/rennokki/reddit-json-api)

Reddit JSON API is a PHP wrapper for handling JSON information from public subreddits.

## 🤝 Supporting

If you are using one or more Renoki Co. open-source packages in your production apps, in presentation demos, hobby projects, school projects or so, spread some kind words about our work or sponsor our work via Patreon. 📦

You will sometimes get exclusive content on tips about Laravel, AWS or Kubernetes on Patreon and some early-access to projects or packages.

[<img src="https://c5.patreon.com/external/logo/become_a_patron_button.png" height="41" width="175" />](https://www.patreon.com/bePatron?u=10965171)

## 🚀 Installation

You can install the package via composer:

```bash
composer require rennokki/reddit-json-api
```

## 🙌 Usage

```php
use Rennokki\RedditApi\Reddit;

$app = Reddit::app(
    'renoki-co/reddit-json-api',
    '2.0',
    'web',
    'someusername'
);

$subreddit = Reddit::subreddit(
    'funny', // subreddit name
    $app
);

$posts = $subreddit->get();

foreach ($posts as $post) {
    $id = $post['id'];
}
```

When retrieving posts, the results are wrapped in a `Rennokki\RedditApi\RedditList` class. This class is based on Laravel Collection and you can pipeline actions on it more easily. Please see [Laravel Collections documentantion](https://laravel.com/docs/master/collections).

## Pagination

For pagination purposes, you shall call `nextPage()` from the previous `$posts`:

```php
$subreddit = Reddit::subreddit('funny', $app);

$posts = $subreddit->get();

$nextPageOfPosts = $posts->nextPage();
```

## Sorting

Reddit allows sorting by posts type. The currently used ones are:

```php
public static $sorts = [
    'hot', 'new', 'controversial', 'top', 'rising',
];
```

To apply the sorting, you should call `sort()`:

```php
$subreddit = Reddit::subreddit('funny', $app);

$subreddit->sort('top');
```

## Time Filtering

Same as sorting, time filters are only a few:

```php
public static $times = [
    'hour', 'day', 'week', 'month', 'year', 'all',
];
```

```php
$subreddit = Reddit::subreddit('funny', $app);

// Top, all time sorting.
$subreddit
    ->sort('top')
    ->time('all');
```

## Limit

By default, each call gives you `20` posts.

```php
$subreddit = Reddit::subreddit('funny', $app);

$subreddit->setLimit(100);
```

## Debugging

If you wish to inspect the URL that is being called, you ca do so:

```php
$subreddit = Reddit::subreddit('funny', $app);

$subreddit
    ->setLimit(30)
    ->sort('top')
    ->time('week');

$url = $subreddit->getCallableUrl();
```

## 🐛 Testing

``` bash
vendor/bin/phpunit
```

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 🔒  Security

If you discover any security related issues, please email alex@renoki.org instead of using the issue tracker.

## 🎉 Credits

- [Alex Renoki](https://github.com/rennokki)
- [All Contributors](../../contributors)
