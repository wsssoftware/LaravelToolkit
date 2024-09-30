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
