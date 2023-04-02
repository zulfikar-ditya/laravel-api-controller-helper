# Laravel Api Controller Helper 
This is my controller api helper for laravel app

## Usage

> - Update your Handler.php
> - Move file ControllerHelper.php to App\Http\Helpers
> - then add ControllerHelper trait in \App\Http\Controllers\Controller

```php
...
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ControllerHelpers;
...
```


Simple use:
```
$users = \App\Models\User::all();

return $this->reponseJsonData($users);
```
