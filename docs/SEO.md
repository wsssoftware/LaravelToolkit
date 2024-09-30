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
