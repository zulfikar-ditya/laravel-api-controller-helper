# Laravel Api Controller Helper 
This is my controller api helper for laravel app

## Usage

> - Move file ControllerHelpers.php to App\Helpers
> - then add ControllerHelpers trait in \App\Http\Controllers\Controller

```php
...
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ControllerHelpers;
...
```


Simple use:
```php
$users = \App\Models\User::all();

return $this->reponseJsonData($users);
```
