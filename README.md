# Onboard
A Laravel package to help track user onboarding steps.

## Installation:

1. Install the package via composer
```bash
composer require calebporzio/onboard
```
2. Register the Service Provider and Facade in `config/app.php`
```php
'providers' => [
    ...
    Calebporzio\Onboard\OnboardServiceProvider::class,

'aliases' => [
    ...
    Calebporzio\Onboard\OnboardFacade::class,
```
3. Add the `Calebporzio\Onboard\GetsOnboarded` trait to your app's User model
```php
class User extends Model
{
    use \Calebporzio\Onboard\GetsOnboarded;
    ...
```

## Example Configuration:

Configure your steps in your `App\Providers\AppServiceProvider.php`
```php
    ...
    public function boot()
    {
	    Onboard::addStep('Add Your S3 Credentials')
	    	->link('/settings#/bucket')
	    	->cta('Add Them')
	    	->completeIf(function (User $user) {
	    		return $user->hasAddedS3Creds();
	    	});

	    Onboard::addStep('Create an API Token')
	    	->link('/settings#/api')
	    	->cta('Create One')
	    	->completeIf(function (User $user) {
	    		return $user->hasGeneratedApiToken();
	    	});
```
(Work in Progress)
