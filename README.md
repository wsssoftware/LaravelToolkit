# A Laravel Toolkit with basics tools

[![Packagist Version](https://img.shields.io/packagist/v/wsssoftware/laraveltoolkit)](https://packagist.org/packages/wsssoftware/laraveltoolkit)
[![NPM Version](https://img.shields.io/npm/v/laraveltoolkit)](https://www.npmjs.com/package/laraveltoolkit)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laraveltoolkit/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/wsssoftware/laraveltoolkit/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laraveltoolkit/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/wsssoftware/laraveltoolkit/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![codecov](https://codecov.io/gh/wsssoftware/laraveltoolkit/graph/badge.svg?token=nzaXcoyc3q)](https://codecov.io/gh/wsssoftware/laraveltoolkit)
[![Packagist Downloads](https://img.shields.io/packagist/dt/wsssoftware/laraveltoolkit?label=Packagist%20downloads)](https://packagist.org/packages/wsssoftware/laraveltoolkit)
[![NPM Downloads](https://img.shields.io/npm/d18m/laraveltoolkit?label=NPM%20downloads)](https://www.npmjs.com/package/laraveltoolkit)

A helpful and useful tools for Laravel projects integrated with Vue, Inertia, Primevue and others.

## Installation Laravel

You can install the package via composer:

```bash
composer require wsssoftware/laraveltoolkit
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laraveltoolkit-config"
```

You can publish the sitemap config file with:

```bash
php artisan vendor:publish --tag="laraveltoolkit-sitemap"
```

## Installation JS

```bash
npm i -D laraveltoolkit
```

To Laravel Toolkit be able to compile its tailwind you must add this line on tailwind config file

```js
    content: [
        // ...
        './node_modules/laraveltoolkit/resources/**/*.{js,vue,ts}',
        // ...
    ]
```

## Usage

### Flash

Flash help you send messages for UI.

```php
use LaravelToolkit\Facades\Flash;
use LaravelToolkit\Flash\Severity;

// You can use this severities
Flash::success('Success Test');
Flash::info('Info Test');
Flash::warn('Warn Test');
Flash::error('Error Test');
Flash::secondary('Secondary Test');
Flash::contrast('Contrast Test');

// You can pass a summary
Flash::success('Message detail', 'Success!');

// You can mark flash as closable or unclosable
Flash::success('Success Test closable')->closable();
Flash::success('Success Test unclosable')->unclosable();

// You can pass a lifetime in seconds to your flash
Flash::success('Success Test with life')->withLife(2000);

// Your message can belong to another group (renders in another primevue group)
Flash::success('Success Test in other group')->withGroup('foo_bar');

// You can test flash using
 Flash::assertFlashed(Severity::SUCCESS, 'User saved!');
 Flash::assertNotFlashed(Severity::SUCCESS, 'User saved!');
```

You must first configure primevue Toast service following [this guide](https://primevue.org/toast/) and then put `ToastReceiver`
component on some Vue layout or where its needed.

```vue

<template>
    <div>
        <ToastReceiver @close="event" @life-end="event"/>
        <!-- or just  <ToastReceiver/>-->
        <slot/>
    </div>
</template>

<script lang="ts">
    import {defineComponent} from "vue";
    import {ToastReceiver} from 'laraveltoolkit';
    import {Message} from "laraveltoolkit/resources/js/Flash";

    export default defineComponent({
        name: "Flash",
        components: {
            ToastReceiver,
        },
        mounted() {

        },
        methods: {
            event(message: Message) {
                console.log(message)
            }
        }
    });
</script>
```

### LINK
Provide an extension to be able to route between subdomains.

In Vue component, when href is appointed to another domain, its use a html default link instead Inertia Link

```vue
<template>
<!--  Use a Inertia component -->
    <Link :href="route('index')">Same domain</Link>
    
<!--  Use a <a/> element -->
    <Link :href="route('another.domain')">Another domain</Link>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {Link} from "laraveltoolkit";

export default defineComponent({
    name: "LinkComponent",
    components: {
        Link,
    }
    
});
</script>

```

To do Post and other methods that send data, you need to configure meta csrf to request be successful

```bladehtml
<meta name="csrf-token" content="{{ csrf_token() }}">
```

Additionally by default, this package extend Laravel Redirector class to enable a redirects to another domains when request was made from Inertia.
You can disable this behavior on config file.

### SEO
Provide an easy way to use SEO on your application.

```php
use Illuminate\Http\Request;
use Inertia\Response;
use LaravelToolkit\Facades\SEO;
use LaravelToolkit\SEO\Image;

class ExampleController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        SEO::withTitle('Client Orders')
            ->withoutDescription('A long description of this page')
            ->withCanonical('https://foo.com')
            ->withOpenGraphImage(new Image('public', 'seo.webp'))
            ->withTwitterCardImage(new Image('public', 'seo.webp', 'alt title'));
            
        // If you want to remove a property you can call methods prefixed with `without`
        SEO::withoutOpenGraphImage();
            
        return Inertia::render('Example');
    }
}
```

On your views vue `seo` blade component to this works on non JS crawlers
```bladehtml
<head>
    ...
    <x-seo/>
    ...
</head>
```

On your Vue layout load Head component to update head properties when navigate between pages.
```vue
<template>
    <div>
        <Head/>
    </div>
</template>

<script lang="ts">
    import {defineComponent} from "vue";
    import {Head} from "laraveltoolkit";

    export default defineComponent({
        name: "TestLayout",
        components: {
            Head,
        }
    });
</script>
```

SEO facade also provide some utils methods

```php
use LaravelToolkit\Facades\SEO;

// Returns true if request agent is a crawler
SEO::isCrawler();
// or
SEO::isCrawler('user agent');

// Transform a human string into a pretty wrote url string
SEO::friendlyUrlString('A example of string!')
// returns 'a-example-of-string'
```

### Sitemap
Provide a way to inform for Search engines the pages of your site.

in routes/sitemap.php (after publishes it):
```php
use App\Models\User;
use LaravelToolkit\Facades\Sitemap;

// You can pass only url
Sitemap::addUrl(route('index'));
// Or a complete info (url, last modified, change frequency and priority
Sitemap::addUrl(route('login'), today()->subDay(), ChangeFrequency::DAILY, 0.5);

// You can interact with queries or collections
Sitemap::fromQuery(User::query(), function (User $user) {
    Sitemap::addUrl(route('index.user', $user->id), $user->created_at);
});
Sitemap::fromCollection(collect([10, 53, 29]), function (User $user) {
    Sitemap::addUrl(route('index.user', $user->id));
});

// A group of sitemaps that only will return if domain matches. Other registries out from this group will be ignored
Sitemap::domain('abc.dev.test', function () {
    Sitemap::addUrl(route('login'));
});

// An index group used for huge sitemaps with more than 50k rows or 50MB
Sitemap::index('users', function () {
 
    Sitemap::fromQuery(User::query(), function (User $user) {
        Sitemap::addUrl(route('index.user', $user->id), $user->created_at);
    });
});
// will be accessible on `route('lt.sitemap_group', "users");`

// then to put this index on main sitemap use:
Sitemap::addIndex('users');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Allan Mariucci Carvalho](https://github.com/wsssoftware)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
