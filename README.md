This package takes a 'convention over configuration' approach, meaning that instead of updating a configuration
in order to add settings, simply add a class to a predefined directory and the setting will be created and stored
in the database according to the name of the class. For example a settings class with a name `SomeSetting` will be stored by default as
`some_setting`.

## Install

Next add the following service provider in `config/app.php`.

``` php
'providers' => [
  SMST\SettingsLaravel\ServiceProvider::class,
],
```

Next publish the package's configuration file by running:

``` bash
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
```

To add a directory a directory where settings will be loaded from, add one or more directory paths
to the generated configuration `paths`:

``` php
'paths' => [
  path\to\settings\dir,
],
```

Run `php artisan settings:sync` to sync the settings in your settings director(ies) to your database.

To create a setting, create a class in your settings directory with the class name corresponding to what you want to name your setting:
``` php
'paths' => [
  path\to\settings\dir,
],
```

To trigger validation on settings values, you can add create a `$rules` instance variable on your settings class:

``` php
class ExampleNumericSetting extends Setting
{
    protected $rules = [
        IsNumeric::class
    ];
}
```

This will trigger an error if anything other than a numeric value is attempted to be set.

You can always mix and match rules:

``` php
class ExampleRequiredNumericSetting extends Setting
{
    protected $rules = [
        IsNumeric::class,
        Required::class
    ];
}
```

The full list of available rules are:

```
IsBoolean
IsNumeric
IsString
Required
```
