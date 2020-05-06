![alt text](https://cdn.marshmallow-office.com/media/images/logo/marshmallow.transparent.red.png "marshmallow.")

# Marshmallow Pages
Deze package geeft de mogelijkheid om gemakkelijk pagina's te beheren in Laravel. Dit is eigelijk een verzameling van handinge composer packages van derde en samengevoegd om snel te kunnen hergebruiken.

### Installatie
```
composer require marshmallow/multi-language
```

Run `php artisan migrate` to create the languages table where we will store the languages.
Run `php artisan marshmallow:resource Language MultiLanguage` to create the Nova resources so you can add more languages if needed.
Add `MultiLanguageMiddleware` to your `app/Http/Kernel.php` so the language that is picked by your visitor can be stored in a session and will be retreived on every request.
```php
protected $middlewareGroups = [
    'web' => [
        //...
        \Illuminate\Session\Middleware\StartSession::class,

        /**
         * Make sure you put this below the StartSession middleware
         */
        \Marshmallow\MultiLanguage\Http\Middleware\MultiLanguageMiddleware::class,
        
        //...
    ],
```

## Migrate
If you have content in a table that needs to be made translatable, you can run the artisan command below:
`php artisan marshmallow:translate-resource Marshmallow\\Pages\\Models\\Page`

## Add the language switcher to Nova
Add the language tool to your `NovaServiceProvider` so you can switch the language you are working on. This is not required but is a good helper.
```php
public function tools()
{
    return [
        new \Digitalcloud\MultilingualNova\NovaLanguageTool,
    ];
}
```

If you use this, you need to publish the config for this package so you can tell this package it can get the languages from the database.
Run `php artisan vendor:publish --provider="Digitalcloud\MultilingualNova\FieldServiceProvider" --tag=config`.
Next change `source` to `database` in `config/multilingual.php`.
Next change `database.model` to `Marshmallow\\MultiLanguage\\Models\\Language`.

## Prepare your models
First make sure you are able to create translations by updating your Nova resource.
```php
public function fields(Request $request)
{
	return [
		// ...
		Multilingual::make('Language'),
	]
}
```

Next update your model so we know which columns are translatable.
```php
use Marshmallow\MultiLanguage\Traits\TranslatableRoute;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations, TranslatableRoute;
    public $translatable = ['name', 'slug', 'layout'];
}
```

## Usage
The route below will be implemented by default. You can use this route to change the current selected language.
```php
Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});
```

## TranslatableRoute trait
This trait will provide two methods.
```php
public function route ()
{
	return '/' . App::getLocale() . $this->routePrefix() . $this->slug;
}

protected function routePrefix ()
{
	return '';
}
```