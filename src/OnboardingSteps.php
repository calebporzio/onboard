<?php

namespace Calebporzio\Onboard;

class OnboardingSteps
{
	protected $steps = [];

	public function addStep($title)
	{
		$this->steps[] = $step = new OnboardingStep($title);

		return $step;
	}

	public function steps($user)
	{
		return collect($this->steps)->map(function($step) use ($user) {
			return $step->setUser($user);
		});
	}
}
