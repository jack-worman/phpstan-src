<?php declare(strict_types = 1);

namespace PHPStan\Type;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Reflection\ExtendedPropertyReflection;
use PHPStan\Reflection\ReflectionProviderStaticAccessor;
use PHPStan\ShouldNotHappenException;
use PHPStan\TrinaryLogic;
use stdClass;

final class ObjectShapePropertyReflection implements ExtendedPropertyReflection
{

	public function __construct(private Type $type)
	{
	}

	public function getDeclaringClass(): ClassReflection
	{
		$reflectionProvider = ReflectionProviderStaticAccessor::getInstance();

		return $reflectionProvider->getClass(stdClass::class);
	}

	public function isStatic(): bool
	{
		return false;
	}

	public function isPrivate(): bool
	{
		return false;
	}

	public function isPublic(): bool
	{
		return true;
	}

	public function getDocComment(): ?string
	{
		return null;
	}

	public function getReadableType(): Type
	{
		return $this->type;
	}

	public function getWritableType(): Type
	{
		return new NeverType();
	}

	public function canChangeTypeAfterAssignment(): bool
	{
		return false;
	}

	public function isReadable(): bool
	{
		return true;
	}

	public function isWritable(): bool
	{
		return false;
	}

	public function isDeprecated(): TrinaryLogic
	{
		return TrinaryLogic::createNo();
	}

	public function getDeprecatedDescription(): ?string
	{
		return null;
	}

	public function isInternal(): TrinaryLogic
	{
		return TrinaryLogic::createNo();
	}

	public function isAbstract(): TrinaryLogic
	{
		return TrinaryLogic::createNo();
	}

	public function isFinal(): TrinaryLogic
	{
		return TrinaryLogic::createNo();
	}

	public function isVirtual(): TrinaryLogic
	{
		return TrinaryLogic::createNo();
	}

	public function hasHook(string $hookType): bool
	{
		return false;
	}

	public function getHook(string $hookType): ExtendedMethodReflection
	{
		throw new ShouldNotHappenException();
	}

	public function isProtectedSet(): bool
	{
		return false;
	}

	public function isPrivateSet(): bool
	{
		return false;
	}

}
