<?php

namespace Calebporzio\Onboard;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Calebporzio\Onboard\OnboardingSteps
 */
class OnboardFacade extends Facade
{
	/**
	* Get the registered name of the component.
	*
	* @return string
	*/
	protected static function getFacadeAccessor()
	{
	    return 'Calebporzio\Onboard\OnboardingSteps';
	}
}
