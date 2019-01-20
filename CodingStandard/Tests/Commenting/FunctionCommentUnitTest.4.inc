<?php

/**
 * Wrong return type.
 *
 * @return $this
 */
function someFunc() {
    return $this;
}

interface InterfaceExample
{

	/**
	 * Short description.
	 *
	 * @param string $param1 Parameter one.
	 *
	 * @return void
	 * @throws \InvalidArgumentException When browser configuration is already registered.
	 */
	public function register($param1);

}

abstract class AbstractClass
{

	/**
	 * Short description.
	 *
	 * @param string $param1 Parameter one.
	 *
	 * @return void
	 * @throws \InvalidArgumentException When browser configuration is already registered.
	 */
	public abstract function register($param1);

}

/**
 * {@inheritdoc}
 */
public function functionName($param1)
{

}
