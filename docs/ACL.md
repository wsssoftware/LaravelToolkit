## ACL
A minimalist implementation of an access control level

Before you need create vendor migrations running:
```bash
php artisan vendor:publish --tag=laraveltoolkit-migrations
```

after you must create table columns for each policy that you want.
```php
/// on database/migrations/2024_10_22_104112_create_user_permissions_table
 Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->json('users')->nullable(); // <-- HERE
            $table->json('products')->nullable(); // <-- HERE
            $table->json('categories')->nullable(); // <-- HERE
            $table->timestamps();

            $table->index(["id", "model", "field"]);
//...
```
> You can create alter tables after as you need to create more columns.

after make UserPermission model running command:
```bash
php artisan make:acl-model
```

Register it on you `AppServiceProvider`
```php
use App\Models\UserPermission;
use LaravelToolkit\Facades\ACL;

//...
public function boot(): void
{
    ACL::withModel(UserPermission::class);
    //OR if you want to use role system, create an string enum and declare its FQN here
     ACL::withModel(UserPermission::class)
            ->withRolesEnum(UserRole::class);
}
```
> Role string Enum must implement `LaravelToolkit\ACL\HasDenyResponse` interface to be used. 

Than on created model declare your rules:
```php
protected static function declarePoliciesAndRoles(): void
{
  self::registryPolicy('users', 'Users', 'Manage system users')
      ->crud()
      ->rule('export', 'Export', 'Export users')
  // OR its equivalent
  self::registryPolicy('users', 'Users', 'Manage system users')
      ->rule('create', 'Create', 'Create users')
      ->rule('read', 'Read', 'Read users')
      ->rule('update', 'Update', 'Update users')
      ->rule('delete', 'Delete', 'Delete users')
      ->rule('export', 'Export', 'Export users');
}
```

On your user Model add `HasUserPermission` to configure relations and other things:
```php

use LaravelToolkit\ACL\HasUserPermission;

class User extends Authenticatable
{
    use HasUserPermission;
```

To use gate on frontend registry it on `HandleInertiaRequests`
```php

use LaravelToolkit\Facades\ACL;

public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'acl' => fn() => ACL::gatePermissions(),
    ];
}
```

### Editing an policy

You can edit using:
```php
$user = \Illuminate\Support\Facades\Auth::user();

$userPermission = $user->userPermission

$users = $userPermission->users;
$users->create->value = true;
$userPermission->users = $users;
// OR
$userPermission->users = [
    'create' => true,
    'read' => true,
    'update' => true,
    'delete' => true,
];
// OR
$userPermission->fillPolicies([
    'users::create' => true,
    'users::read' => true,
    'users::update' => true,
    'users::delete' => true,
]);
// OR
$userPermission->grantAll();
// OR
$userPermission->grantAll('users');
// OR
$userPermission->denyAll();
// OR
$userPermission->denyAll('users');
//then
$userPermission->save();
```

If you want edit on frontend:

```php

use LaravelToolkit\Facades\ACL;
use LaravelToolkit\ACL\Policy;
// on controller or equivalent
public function create(=): Response
{
    return Inertia::render('Tests/LaravelToolkit/ACL', [
        'permissions' => ACL::permissions(),
        // or if you want to filter edit permissions available por users
        'permissions' => ACL::permissions(filter: function (Policy $policy) {
            return !str_starts_with($policy->column, 'admin_');
        }),
    ]);
}

public function store(Request $request): RedirectResponse
{
    $up = \Illuminate\Support\Facades\Auth::user()->userPermission;
    $up->fillPolicies($request->permissions);
    $up->save();
    return retirect()->route('index');
}
```

```vue
<template>
    <form @submit.prevent="submit">
        <UserPermissionsEditor v-model="form.permissions" :permissions="permissions"/>
        <button type="submit">Enviar</button>
    </form>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {UserPermissions, UserPermissionsEditor} from "laraveltoolkit";
import {useForm} from "@inertiajs/vue3";

export default defineComponent({
    name: "ACL" ,
    components: {UserPermissionsEditor},
    props: {
        permissions: {
            type: Array as PropType<UserPermissions>,
            required: true,
        }
    },
    data() {
        return {
            form: useForm({
                permissions: {}
            }),
        };
    },
    methods: {
        submit(): void {
            this.form.post(route('send'))
        }
    },
});
</script>
```

### Usage

On backend, you will use like a normal gate, but pay attention on ability name:
```php
// policyColumn + :: + ruleName
\Illuminate\Support\Facades\Gate::allows('users::create')
```

On frontend, when you have been installed the VueJS plugin, you can use `$gate class`, `Gate component` or `Gate directive`.

```vue
<template>
    <Gate rule="allows" abilities="users::read">
        <h1>Show this on has</h1>
        <template #fallback>
            <h1>Or show this on hasn't</h1>
        </template>
    </Gate>
    <button v-gate:allows="'users::read'">You see if you has</button>
    <button v-if="has">You see if you has</button>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {GateDirective} from "laraveltoolkit";
import {Gate} from "Laraveltoolkit";

export default defineComponent({
    name: "Example",
    components: {Gate},
    directives: {
        gate: GateDirective,
    },
    computed: {
        has(): boolean {
            return this.$gate.allows('users.read')
        }
    },
});
</script>

```


