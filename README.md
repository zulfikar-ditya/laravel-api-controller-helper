# Laravel Api Controller Helper 
This is my controller api helper for laravel app

## Usage

> - Move file ControllerHelpers.php to App\Traits
> - then add ControllerHelpers trait in \App\Http\Controllers\Controller

```php

...
 use App\Traits\ControllerHelpers;
...

...
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ControllerHelpers;
...
```


Simple use:
```php
$users = \App\Models\User::all();

return $this->reponseJsonData($users);
```
