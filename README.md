# Features

-   [x] Make Payments using Dischub
-   [x] check payments status
-   [x] documentation [Docs](https://dischub.co.zw/features/developer_documentation)

### Things to take note

-[] Make sure the call back is public using `boostrap/app.php`

```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'dischub/callback', // Exclude this route from CSRF protection
        ]);
    })
```
