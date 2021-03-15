<?php

use Calebporzio\Onboard\OnboardingSteps;
use Calebporzio\Onboard\OnboardingManager;
use PHPUnit\Framework\TestCase;

class OnboardTest extends TestCase
{
    /**
     * Dummy user object.
     */
    protected $user;

    protected function setUp()
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
            ->completeIf(function () {
                return true;
            });

        $onboarding = new OnboardingManager($this->user, $onboardingSteps);

        $this->assertTrue($onboarding->finished());
        $this->assertFalse($onboarding->inProgress());
    }

    /** @test */
    public function it_returns_the_correct_next_unfinished_step()
    {
        $onboardingSteps = new OnboardingSteps;
        $onboardingSteps->addStep('Step 1')
            ->link("/step-1")
            ->completeIf(function () {
                return true;
            });

        $onboardingSteps->addStep('Step 2')
            ->link("/step-2")
            ->completeIf(function () {
                return false;
            });

        $onboardingSteps->addStep('Step 3')
            ->link("/step-3")
            ->completeIf(function () {
                return false;
            });

        $onboarding = new OnboardingManager($this->user, $onboardingSteps);

        $nextStep = $onboarding->nextUnfinishedStep();

        $this->assertNotNull($nextStep);
        $this->assertEquals("Step 2", $nextStep->title);
        $this->assertEquals("/step-2", $nextStep->link);
    }

    /** @test */
    public function nextUnfinishedStep_returns_null_if_all_steps_are_completed()
    {
        $onboardingSteps = new OnboardingSteps;
        $onboardingSteps->addStep('Step 1')
            ->completeIf(function () {
                return true;
            });

        $onboardingSteps->addStep('Step 2')
            ->completeIf(function () {
                return true;
            });

        $onboardingSteps->addStep('Step 3')
            ->completeIf(function () {
                return true;
            });

        $onboarding = new OnboardingManager($this->user, $onboardingSteps);

        $nextStep = $onboarding->nextUnfinishedStep();

        $this->assertNull($nextStep);
    }

    /** @test */
    public function the_proper_object_gets_passed_into_completion_callback()
    {
        $user = $this->getMock('User', ['testMe']);
        $user->expects($this->once())->method('testMe');

        $onboardingSteps = new OnboardingSteps;
        $onboardingSteps->addStep('Test Step')
            ->completeIf(function ($user) {
                // if this gets called, it ensures that the right object was passed through.
                $user->testMe();
                return true;
            });

        $onboarding = new OnboardingManager($user, $onboardingSteps);

        // Calling finished() will trigger the completeIf callback.
        $this->assertTrue($onboarding->finished());
    }
}
