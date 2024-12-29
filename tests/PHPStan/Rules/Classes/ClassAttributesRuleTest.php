<?php declare(strict_types = 1);

namespace PHPStan\Rules\Classes;

use PHPStan\Rules\AttributesCheck;
use PHPStan\Rules\ClassCaseSensitivityCheck;
use PHPStan\Rules\ClassForbiddenNameCheck;
use PHPStan\Rules\ClassNameCheck;
use PHPStan\Rules\FunctionCallParametersCheck;
use PHPStan\Rules\NullsafeCheck;
use PHPStan\Rules\PhpDoc\UnresolvableTypeHelper;
use PHPStan\Rules\Properties\PropertyReflectionFinder;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;
use const PHP_VERSION_ID;

/**
 * @extends RuleTestCase<ClassAttributesRule>
 */
class ClassAttributesRuleTest extends RuleTestCase
{

	private bool $checkExplicitMixed = false;

	private bool $checkImplicitMixed = false;

	protected function getRule(): Rule
	{
		$reflectionProvider = $this->createReflectionProvider();
		return new ClassAttributesRule(
			new AttributesCheck(
				$reflectionProvider,
				new FunctionCallParametersCheck(
					new RuleLevelHelper($reflectionProvider, true, false, true, $this->checkExplicitMixed, $this->checkImplicitMixed, false),
					new NullsafeCheck(),
					new UnresolvableTypeHelper(),
					new PropertyReflectionFinder(),
					true,
					true,
					true,
					true,
				),
				new ClassNameCheck(
					new ClassCaseSensitivityCheck($reflectionProvider, false),
					new ClassForbiddenNameCheck(self::getContainer()),
				),
				true,
			),
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/class-attributes.php'], [
			[
				'Attribute class ClassAttributes\Nonexistent does not exist.',
				22,
			],
			[
				'Class ClassAttributes\Foo is not an Attribute class.',
				28,
			],
			[
				'Class ClassAttributes\Bar referenced with incorrect case: ClassAttributes\baR.',
				34,
			],
			[
				'Attribute class ClassAttributes\Baz does not have the class target.',
				46,
			],
			[
				'Attribute class ClassAttributes\Bar is not repeatable but is already present above the class.',
				59,
			],
			[
				'Attribute class self does not exist.',
				65,
			],
			[
				'Attribute class ClassAttributes\AbstractAttribute is abstract.',
				77,
			],
			[
				'Attribute class ClassAttributes\Bar does not have a constructor and must be instantiated without any parameters.',
				83,
			],
			[
				'Constructor of attribute class ClassAttributes\NonPublicConstructor is not public.',
				100,
			],
			[
				'Attribute class ClassAttributes\AttributeWithConstructor constructor invoked with 0 parameters, 2 required.',
				118,
			],
			[
				'Attribute class ClassAttributes\AttributeWithConstructor constructor invoked with 1 parameter, 2 required.',
				119,
			],
			[
				'Unknown parameter $r in call to ClassAttributes\AttributeWithConstructor constructor.',
				120,
			],
			[
				'Interface ClassAttributes\InterfaceAsAttribute is not an Attribute class.',
				132,
			],
			[
				'Trait ClassAttributes\TraitAsAttribute is not an Attribute class.',
				142,
			],
			[
				'Attribute class ClassAttributes\FlagsAttributeWithPropertyTarget does not have the class target.',
				164,
			],
		]);
	}

	public function testRuleForEnums(): void
	{
		if (PHP_VERSION_ID < 80100) {
			$this->markTestSkipped('Test requires PHP 8.1.');
		}

		$this->analyse([__DIR__ . '/data/enum-attributes.php'], [
			[
				'Attribute class EnumAttributes\AttributeWithPropertyTarget does not have the class target.',
				23,
			],
			[
				'Enum EnumAttributes\EnumAsAttribute is not an Attribute class.',
				35,
			],
		]);
	}

	public function testBug7171(): void
	{
		$this->analyse([__DIR__ . '/data/bug-7171.php'], [
			[
				'Parameter $repositoryClass of attribute class Bug7171\Entity constructor expects class-string<Bug7171\EntityRepository<T of object>>|null, \'stdClass\' given.',
				66,
			],
		]);
	}

	public function testAllowDynamicPropertiesAttribute(): void
	{
		$this->analyse([__DIR__ . '/data/allow-dynamic-properties-attribute.php'], []);
	}

	public function testBug12011(): void
	{
		if (PHP_VERSION_ID < 80300) {
			$this->markTestSkipped('Test requires PHP 8.3.');
		}

		$this->checkExplicitMixed = true;
		$this->checkImplicitMixed = true;
		$this->analyse([__DIR__ . '/data/bug-12011.php'], [
			[
				'Parameter #1 $name of attribute class Bug12011\Table constructor expects string|null, int given.',
				23,
			],
		]);
	}

	public function testBug12281(): void
	{
		if (PHP_VERSION_ID < 80200) {
			$this->markTestSkipped('Test requires PHP 8.2.');
		}

		$this->checkExplicitMixed = true;
		$this->checkImplicitMixed = true;
		$this->analyse([__DIR__ . '/data/bug-12281.php'], [
			[
				'Attribute class AllowDynamicProperties cannot be used with readonly class.',
				05,
			],
			[
				'Attribute class AllowDynamicProperties cannot be used with enum.',
				12,
			],
			[
				'Attribute class AllowDynamicProperties cannot be used with interface.',
				15,
			],
		]);
	}

}
