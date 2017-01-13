<?php

namespace Calebporzio\Onboard;

use Illuminate\Support\Facades\Facade;

class OnboardFacade extends Facade
{
	protected static function getFacadeAccessor()
	{
	    return 'Calebporzio\Onboard\OnboardingSteps';
	}
}