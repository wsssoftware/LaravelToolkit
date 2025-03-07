## Deploy and Maintenance mode 
A toolset to help on deploy application and maintenance mode

First to use maintenance mode, if you use Laravel Echo events you must call function on your echo.js. Your file will be like this:
```js
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import { Deploy } from "laraveltoolkit";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

Deploy(); // Register channels
```

Then you must to create `resources/js/Pages/Maintenance.vue` file to be the maintenance page