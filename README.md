# Installation Local

**Install Dependencies Using Docker**

```bash

docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php83-composer:latest \
composer install --ignore-platform-reqs && yarn 
```


**Migrate**
```bash
cp .env.example .env
sail artisan migrate
```

## Expected Goal

Register multiple panels using dynamic configuration stored in the database.

For this example, I want to register panels with dynamic colors specified in the database.

In reality, my issue is not about colors specifically, as they are not very useful in this context. The real challenge is extending plugins or other panel functions dynamically.

## Possible Solutions

1. Use closures for plugins (e.g., `fn() => ...`) or any other configurations like `id(fn() => ...)`, ensuring they are called when Filament boots.

2. Handle `Filament::register` after Filament has booted. The facade's `->resolving()` method prevents calls if the provider has already been booted.

3. Add priority boot ordering in Laravel's core, similar to the middleware priority list, to enforce panel booting between the `DatabaseProvider` and `FilamentProvider`.

4. Any other solutions can be usefull.

## Providers

See `bootstrap/providers.php`.

- **AdminPanelProviderWorking::class**  

This provider works because I can loop over `$tenants` in `register`, as it does not depend on database queries.

- **AdminPanelProviderDatabaseNotWorking::class**  

This provider does not work because `register` is called before `Illuminate\Database\DatabaseServiceProvider` is booted, so it cannot query the database.

- **AdminPanelProviderDatabaseSemiWorking::class**  

This provider can query the database because the panel is registered using `boot` instead of `register`. However, Filament cannot register the panel at this point; it only works when using `register`.

- **AdminPanelProviderDatabaseWorking::class**  

  This provider can query the database and uses `boot` to register new panels. However, to make it work, I need to use Reflection to reorder the provider and force it to boot after `DatabaseProvider` and before `FilamentProvider`.
