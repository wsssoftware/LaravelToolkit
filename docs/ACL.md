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
}
 
```

Than on created model declare your rules:
```php
protected static function declarePolicies(): void
{
//  self::registryPolicy('users', 'Usuários', 'Gerencia os usuários do sistema')
//      ->crud();
//  // OR its equivalent
//  self::registryPolicy('users', 'Usuários', 'Gerencia os usuários do sistema')
//      ->rule('create', 'Criar', 'Cria um usuário no sistema')
//      ->rule('read', 'Ler', 'Ler um usuário no sistema')
//      ->rule('update', 'Atualizar', 'Atualizar um usuário no sistema')
//      ->rule('delete', 'Deletar', 'Deletar um usuário no sistema');
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
        'acl' => fn() => ACL::permissions(),
    ];
}
```

That is it! After this, it will generate all gates for you and you can access it typing:
```php
// policyColumn + :: + ruleName
\Illuminate\Support\Facades\Gate::allows('users::create')
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
$userPermission->denyAll();


```
