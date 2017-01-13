<?php

namespace Calebporzio\Onboard;

class OnboardingStep
{
	public $completeIf;

	public $user;

	public $attributes = [];

	public function __construct($title)
	{
		$this->attributes(['title' => $title]);
	}

	public function cta($cta)
	{
		$this->attributes(['cta' => $cta]);

		return $this;
	}

	public function link($link)
	{
		$this->attributes(['link' => $link]);

		return $this;
	}

	public function completeIf(callable $callback)
	{
		$this->completeIf = $callback;

		return $this;
	}

	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	public function complete()
	{
		if ($this->completeIf && $this->user) {
			return call_user_func_array($this->completeIf, [$this->user]) ? true : false;
		}

		return false;
	}

	public function incomplete()
	{
		return ! $this->complete();
	}
	
	public function attribute($key, $default = null)
    {
        return array_get($this->attributes, $key, $default);
    }

    public function attributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

	public function __get($key)
	{
	    return $this->attribute($key);
	}
}