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
