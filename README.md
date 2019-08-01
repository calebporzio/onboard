![](https://raw.githubusercontent.com/calebporzio/onboard/master/onboard-logo.png)

# Onboard
A Laravel package to help track user onboarding steps.

## Installation:

* Install the package via composer
```bash
composer require calebporzio/onboard
```
* Register the Service Provider and Facade in `config/app.php`
```php
'providers' => [
    ...
    Calebporzio\Onboard\OnboardServiceProvider::class,

'aliases' => [
    ...
    Calebporzio\Onboard\OnboardFacade::class,
```
* Add the `Calebporzio\Onboard\GetsOnboarded` trait to your app's User model
```php
class User extends Model
{
    use \Calebporzio\Onboard\GetsOnboarded;
    ...
```

## Example Configuration:

Configure your steps in your `App\Providers\AppServiceProvider.php`
```php

use App\User;
use Calebporzio\Onboard\OnboardFacade;

class AppServiceProvider extends ServiceProvider
{
    // ...

    public function boot()
    {
	    OnboardFacade::addStep('Complete Profile')
	    	->link('/profile')
	    	->cta('Complete')
	    	->completeIf(function (User $user) {
	    		return $user->profile->isComplete();
	    	});

	    OnboardFacade::addStep('Create Your First Post')
	    	->link('/post/create')
	    	->cta('Create Post')
	    	->completeIf(function (User $user) {
	    		return $user->posts->count() > 0;
	    	});
```

## Usage:
Now you can access these steps along with their state wherever you like. Here is an example blade template:
```php
@if (auth()->user()->onboarding()->inProgress())
	<div>

		@foreach (auth()->user()->onboarding()->steps as $step)
			<span>
				@if($step->complete())
					<i class="fa fa-check-square-o fa-fw"></i>
					<s>{{ $loop->iteration }}. {{ $step->title }}</s>
				@else
					<i class="fa fa-square-o fa-fw"></i>
					{{ $loop->iteration }}. {{ $step->title }}
				@endif
			</span>
						
			<a href="{{ $step->link }}" {{ $step->complete() ? 'disabled' : '' }}>
				{{ $step->cta }}
			</a>
		@endforeach

	</div>
@endif
```
Check out all the available features below:
```php
$onboarding = Auth::user()->onboarding();

$onboarding->inProgress();

$onboarding->finished();

$onboarding->steps()->each(function($step) {
	$step->title;
	$step->cta;
	$step->link;
	$step->complete();
	$step->incomplete();
});
```
Definining custom attributes and accessing them:
```php
// Defining the attributes
OnboardFacade::addStep('Step w/ custom attributes')
	->attributes([
		'name' => 'Waldo',
		'shirt_color' => 'Red & White',
	]);

// Accessing them
$step->name;
$step->shirt_color;
```


## Example middleware

If you want to ensure that your user is redirected to the next unfinished onboarding step, whenever they access your web application, you can use the following middleware as a starting point:

```php
<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RedirectToUnfinishedOnboardingStep
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->onboarding()->inProgress()) {
            return redirect()->to(
                auth()->user()->onboarding()->nextUnfinishedStep()->link
            );
        }
        
        return $next($request);
    }
}
```

**Quick tip**: Don't add this middleware to routes that update the state of the onboarding steps, your users will not be able to progress because they will be redirected back to the onboarding step.
