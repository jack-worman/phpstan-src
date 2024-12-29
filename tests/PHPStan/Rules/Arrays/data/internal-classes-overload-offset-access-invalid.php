<?php
/**
 * All of these offset accesses are invalid
 * ++ and -- are also disallowed in general are they operate "by ref"
 */
namespace InternalClassesOverloadOffsetAccessInvalid;

function test1(\DOMNamedNodeMap $v): void
{
	$v[] = 'append';
	$v[0] = 'update';
	$v[0] .= ' and again';
	$r1 = &$v[0];
	unset($v[0]);
	var_dump($r1);
}

function test3(\DOMNodeList $v): void
{
	$v[] = 'append';
	$v[0] = 'update';
	$v[0] .= ' and again';
	$r1 = &$v[0];
	unset($v[0]);
	var_dump($r1);
}

function test7(\PDORow $v): void
{
	$v[] = 'append';
	$v[0] = 'update';
	$v[0] .= ' and again';
	$r1 = &$v[0];
	unset($v[0]);
	var_dump($r1);
}

function test8(\ResourceBundle $v): void
{
	if ($v[0]) {
		var_dump($v[0]);
	}
	$v[] = 'append';
	$v[0] = 'update';
	$v[0] .= ' and again';
	$r1 = &$v[0];
	unset($v[0]);
	var_dump($r1);
	var_dump($v['name']);
	var_dump($v[0]);
}

