![](https://raw.githubusercontent.com/calebporzio/onboard/master/onboard-logo.png)

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
	    Onboard::addStep('Complete Profile')
	    	->link('/profile')
	    	->cta('Complete')
	    	->completeIf(function (User $user) {
	    		return $user->profile->isComplete();
	    	});

	    Onboard::addStep('Create Your First Post')
	    	->link('/post/create')
	    	->cta('Create Post')
	    	->completeIf(function (User $user) {
	    		return $user->posts->count() > 0;
	    	});
```
Now you can access these steps along with their state wherever you like. Here is an example blade template:
```php
@if (Auth::user()->onboarding()->inProgress())
	<div>

		@foreach (Auth::user()->onboarding()->steps as $step)
			<span>
				@if($step->complete())
					<i class="fa fa-check-square-o fa-fw" ></i>
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
}
```
Definining custom attributes and accessing them:
```php
// Defining the attributes
Onboard::addStep('Step w/ custom attributes')
	->attributes([
		'name' => 'Waldo',
		'shirt_color' => 'Red & White',
	]);

// Accessing them
$step->name;
$step->shirt_color;
```
