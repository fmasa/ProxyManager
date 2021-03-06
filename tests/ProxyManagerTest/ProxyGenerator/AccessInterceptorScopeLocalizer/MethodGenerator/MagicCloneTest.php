<?php

declare(strict_types=1);

namespace ProxyManagerTest\ProxyGenerator\AccessInterceptorScopeLocalizer\MethodGenerator;

use PHPUnit\Framework\TestCase;
use ProxyManager\ProxyGenerator\AccessInterceptorScopeLocalizer\MethodGenerator\MagicClone;
use ProxyManagerTestAsset\ClassWithMagicMethods;
use ProxyManagerTestAsset\EmptyClass;
use ReflectionClass;
use Zend\Code\Generator\PropertyGenerator;

/**
 * Tests for {@see \ProxyManager\ProxyGenerator\AccessInterceptorScopeLocalizer\MethodGenerator\MagicClone}
 *
 * @group Coverage
 */
class MagicCloneTest extends TestCase
{
    /**
     * @covers \ProxyManager\ProxyGenerator\AccessInterceptorScopeLocalizer\MethodGenerator\MagicClone::__construct
     */
    public function testBodyStructure() : void
    {
        $reflection = new ReflectionClass(EmptyClass::class);
        /** @var PropertyGenerator|\PHPUnit_Framework_MockObject_MockObject $prefixInterceptors */
        $prefixInterceptors = $this->createMock(PropertyGenerator::class);
        /** @var PropertyGenerator|\PHPUnit_Framework_MockObject_MockObject $suffixInterceptors */
        $suffixInterceptors = $this->createMock(PropertyGenerator::class);

        $prefixInterceptors->expects(self::any())->method('getName')->will(self::returnValue('pre'));
        $suffixInterceptors->expects(self::any())->method('getName')->will(self::returnValue('post'));

        $magicClone = new MagicClone($reflection, $prefixInterceptors, $suffixInterceptors);

        self::assertSame('__clone', $magicClone->getName());
        self::assertCount(0, $magicClone->getParameters());
        self::assertStringMatchesFormat("%a\n\n\$returnValue = null;\n\n%a", $magicClone->getBody());
    }

    /**
     * @covers \ProxyManager\ProxyGenerator\AccessInterceptorScopeLocalizer\MethodGenerator\MagicClone::__construct
     */
    public function testBodyStructureWithInheritedMethod() : void
    {
        $reflection = new ReflectionClass(ClassWithMagicMethods::class);
        /** @var PropertyGenerator|\PHPUnit_Framework_MockObject_MockObject $prefixInterceptors */
        $prefixInterceptors = $this->createMock(PropertyGenerator::class);
        /** @var PropertyGenerator|\PHPUnit_Framework_MockObject_MockObject $suffixInterceptors */
        $suffixInterceptors = $this->createMock(PropertyGenerator::class);

        $prefixInterceptors->expects(self::any())->method('getName')->will(self::returnValue('pre'));
        $suffixInterceptors->expects(self::any())->method('getName')->will(self::returnValue('post'));

        $magicClone = new MagicClone($reflection, $prefixInterceptors, $suffixInterceptors);

        self::assertSame('__clone', $magicClone->getName());
        self::assertCount(0, $magicClone->getParameters());
        self::assertStringMatchesFormat("%a\n\n\$returnValue = parent::__clone();\n\n%a", $magicClone->getBody());
    }
}
