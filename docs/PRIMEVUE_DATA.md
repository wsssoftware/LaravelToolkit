## PrimeVue Data
A minimalist implementation of DataTables and DataView on Laravel

### Frontend (Laravel)
On laravel, basically, you need to do only two tings. 
#### Change route method
```php
// From this
Route::get('/', Controller::class);

// To this
Route::getAndPost('/', Controller::class);
```
> To avoid polluting the URLs with search and sorting parameters, we send the parameters via post in the Inertia `reload` method.

#### Config action `data` prop
```php
use \App\Models\User;
use \Inertia\Inertia;

Inertia::render('Users', [
    'users' => fn() => User::query()->primevueData(),
])
```
> This way, the data from the current pagination page will be loaded together with the first load.

With Lazy load:
```php
use \App\Models\User;
use \Inertia\Inertia;

Inertia::render('Users', [
    'users' => Inertia::lazy(fn() => User::query()->primevueData()),
])
```
> Here, the main page is loaded first, and only then does it load the table data automatically.

With two `data`:
```php
use \App\Models\User;
use \Inertia\Inertia;

Inertia::render('Users', [
    'users' => Inertia::lazy(fn() => User::query()->primevueData()),
    'categories' => Inertia::lazy(fn() => Categories::query()->primevueData('page_categories')),
])
```
> If you do not change the `pageName` attribute when using more than one `data`, some unexpected behavior may occur.

With custom global `$globalFilterColumn` attribute:
```php
use \App\Models\User;
use \Inertia\Inertia;

Inertia::render('Users', [
    'users' => Inertia::lazy(fn() => User::query()->primevueData(globalFilterColumn: 'foo')),
])
```
> In some scenarios, such as when the database table in question has a column called `global`, it may be necessary to change this attribute so that it is possible to search all columns in the table.


### Frontend (PrimeVue)
