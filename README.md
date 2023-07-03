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

## Upload File Helper
some time we need to do some driven design like using service and repository. You can simpify to use the `UploadFile.php` instead the entire controller helpers

```php
use \App\Traits\UploadFile;

...

$this->uploadFile($request->file('file-name'), 'file-folder');

...
$this->deleteFile($model->file_name ?? '');
$this->uploadFile($request->file('file-name'), 'file-folder');
...

```
