<?php // lint >= 8.1

namespace PropertyAssignRef;

class Foo
{

	private readonly int $foo;

	public readonly int $bar;

	public function doFoo()
	{
		$foo = &$this->foo;
		$bar = &$this->bar;
	}

}

class Bar
{

	public function doBar(Foo $foo)
	{
		$a = &$foo->foo; // private
		$b = &$foo->bar;
	}

}

class Baz
{

	protected $a;

	private $b;

}

function (Baz $b): void {
	$z = &$b->a;
	$zz = &$b->b;
};
