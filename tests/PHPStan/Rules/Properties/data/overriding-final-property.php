<?php

namespace OverridingFinalProperty;

class Foo
{

	final public $a;

	final protected $b;

	public private(set) int $c;

	protected private(set) int $d;

}

class Bar extends Foo
{

	public $a;

	public $b;

	public int $c;

	public int $d;

}
