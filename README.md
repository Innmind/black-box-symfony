# black-box-symfony

[![Build Status](https://github.com/innmind/black-box-symfony/workflows/CI/badge.svg?branch=main)](https://github.com/innmind/black-box-symfony/actions?query=workflow%3ACI)
[![Type Coverage](https://shepherd.dev/github/innmind/black-box-symfony/coverage.svg)](https://shepherd.dev/github/innmind/black-box-symfony)

This package is an extension of [`innmind/black-box`](https://packagist.org/packages/innmind/black-box) to help test [Symfony](https://symfony.com) applications.

## Installation

```sh
composer require innmind/black-box-symfony
```

## Usage

```php
use Innmind\BlackBox\{
    Runner\Assert,
    Symfony\Application,
};

return static function() {
    yield test(
        'Login',
        function(Assert $assert) {
            $app = Application::new($assert); // This assumes the kernel class is 'App\Kernel'
            $response = $app
                ->json()
                ->post('/login', [
                    'username' => 'john',
                    'password' => 'doe',
                ]);
            $response
                ->statusCode()
                ->is(200);

            $content = $response->body()->json();
            $assert
                ->array($content)
                ->hasKey('token');
            $token = $content['token'];

            $app
                ->get('/me')
                ->statusCode()
                ->is(200);
        },
    );
};
```

### Model Based Testing

By representing the application as a standalone object we can consider it as a system to test and so test it via properties.

```php
use Innmind\BlackBox\{
    Set,
    Property,
    Runner\Assert,
    Symfony\Application,
};

/**
 * @implements Property<Application>
 */
final class Login implements Property
{
    public static function any(): Set
    {
        return Set\Elements::of(new self);
    }

    public function applicableTo(object $app): bool
    {
        return true;
    }

    public function ensureHeldBy(Assert $assert, object $app): object
    {
        response = $app
            ->json()
            ->post('/login', [
                'username' => 'john',
                'password' => 'doe',
            ]);
        $response
            ->statusCode()
            ->is(200);

        $content = $response->body()->json();
        $assert
            ->array($content)
            ->hasKey('token');
        $token = $content['token'];

        $app
            ->get('/me')
            ->statusCode()
            ->is(200);

        return $app;
    }
}
```

And you would run it via:

```php
use Innmind\BlackBox\{
    Set,
    Properties,
    Runner\Assert,
    Symfony\Application,
};

return static function() {
    yield proof(
        'Login',
        given(Login::any()),
        function(Assert $assert, Login $login) {
            $app = Application::new($assert);

            $login->ensureHeldBy($assert, $app);
        },
    );
    // and you can even test a sequence of properties to simulate a user actions
    yield proof(
        'No user interaction should crash the app',
        given(Set\Properties::any(
            Login::any(),
            AnotherProperty::any(),
            // etc...
        )),
        function(Assert $assert, Properties $properties) {
            $app = Application::new($assert);

            $properties->ensureHeldBy($assert, $app);
        },
    );
}
```
