<?php

use Calebporzio\Onboard\OnboardingSteps;
use Calebporzio\Onboard\OnboardingManager;

class OnboardTest extends PHPUnit_Framework_TestCase
{
	/** @test */
	public function is_in_progress_when_all_steps_are_incomplete()
	{
		$onboardingSteps = new OnboardingSteps;

		$onboardingSteps->addStep('Test Step');
		$onboardingSteps->addStep('Another Test Step');

		$onboarding = new OnboardingManager(new stdClass(), $onboardingSteps);

		$this->assertTrue($onboarding->inProgress());
		$this->assertFalse($onboarding->finished());
	}

	/** @test */
	public function is_finished_when_all_steps_are_complete()
	{
		$onboardingSteps = new OnboardingSteps;

		$onboardingSteps->addStep('Test Step')
			->completeIf(function() { 
				return true; 
			});

		$onboardingSteps->addStep('Another Test Step')
			->completeIf(function() { return true; });

		$onboarding = new OnboardingManager(new stdClass(), $onboardingSteps);

		$this->assertTrue($onboarding->finished());
		$this->assertFalse($onboarding->inProgress());
	}
}
