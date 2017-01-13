<?php

use Calebporzio\Onboard\OnboardingSteps;
use Calebporzio\Onboard\OnboardingManager;

class OnboardTest extends PHPUnit_Framework_TestCase
{
	/** 
	 * Dummy user object.
	 */
	protected $user;

	public function setUp()
	{
		$this->user = $this->getMock('User');
	}

	/** @test */
	public function steps_can_be_defined_and_configured()
	{
		$onboardingSteps = new OnboardingSteps;

		$onboardingSteps->addStep('Test Step')
	    	->link('/some/url')
	    	->cta('Test This!')
	    	->attributes(['another' => 'attribute'])
	    	->completeIf(function () {
	    		return true;
	    	});

	    $this->assertEquals(1, $onboardingSteps->steps(new stdClass())->count());

	    $step = $onboardingSteps->steps(new stdClass())->first();

	    $this->assertEquals('/some/url', $step->link);
	    $this->assertEquals('Test This!', $step->cta);
	    $this->assertEquals('Test Step', $step->title);
	    $this->assertEquals('attribute', $step->another);
	}

	/** @test */
	public function is_in_progress_when_all_steps_are_incomplete()
	{
		$onboardingSteps = new OnboardingSteps;
		$onboardingSteps->addStep('Test Step');
		$onboardingSteps->addStep('Another Test Step');

		$onboarding = new OnboardingManager($this->user, $onboardingSteps);

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

		$onboarding = new OnboardingManager($this->user, $onboardingSteps);

		$this->assertTrue($onboarding->finished());
		$this->assertFalse($onboarding->inProgress());
	}

	/** @test */
	public function the_proper_object_gets_passed_into_completion_callback()
	{
		$user = $this->getMock('User', ['testMe']);
		$user->expects($this->once())->method('testMe');

		$onboardingSteps = new OnboardingSteps;
		$onboardingSteps->addStep('Test Step')
			->completeIf(function($user) { 
				// if this gets called, it ensures that the right object was passed through.
				$user->testMe();
				return true; 
			});

		$onboarding = new OnboardingManager($user, $onboardingSteps);

		// Calling finished() will triger the completeIf callback.
		$this->assertTrue($onboarding->finished());
	}
}
