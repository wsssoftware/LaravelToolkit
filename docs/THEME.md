## Theme
A JS class that handles theme changes

On your bootstrap file put to create an instance and handle the current theme
```js
import {getThemeInstance} from "laraveltoolkit";
getThemeInstance().handle();
```

Using the same class instance you change manage your theme
```js
import {getThemeInstance} from "laraveltoolkit";

// Put the dark theme
getThemeInstance().dark();

// Put the light theme
getThemeInstance().light();

// Put the syste, theme
getThemeInstance().system();

// Current return a Vue Ref string of current theme that change in every
// place that you use it.
let current = getThemeInstance().current;
```

If you want that theme works on all yours subdomain add on .env your base domain
```dotenv
VITE_DOMAIN="${APP_DOMAIN}"
#OR
VITE_DOMAIN="basedomain.com"
```
> This works only for subdomains from same domain
