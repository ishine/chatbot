<?php


namespace Commune\Test\Chatbot\Framework\Utils;


use Commune\Chatbot\Framework\Utils\StringUtils;
use PHPUnit\Framework\TestCase;

/**
 * @property int $abc
 * @property string $abe sdkjfksjdf
 * @property     $bbbb sdjf aksjdfks skd
 */
class StringUtilsTest extends TestCase
{

    /**
     * @description  123 测试一下. 试测, 试吃。二十世纪东方隆
jksdflkjsldkjflsdjkf
lksjdflskjdlfjskdfjlsdjkfslkjdflskjdflkjsfsd*
     *
     */
    public function testFetchDescAnnotation()
    {
        $r = new \ReflectionClass(static::class);
        $doc = $r->getMethod(__FUNCTION__)->getDocComment();

        $desc = StringUtils::fetchDescAnnotation($doc);

        $this->assertEquals('123 测试一下. 试测, 试吃。二十世纪东方隆
jksdflkjsldkjflsdjkf
lksjdflskjdlfjskdfjlsdjkfslkjdflskjdflkjsfsd', $desc);
    }


    public function testFetchPropertyAnnotations()
    {
        $r = new \ReflectionClass(static::class);
        $doc = $r->getDocComment();

        $properties = StringUtils::fetchPropertyAnnotations($doc);

        $this->assertEquals(['abc', ''], $properties[0]);
        $this->assertEquals(['abe', 'sdkjfksjdf'], $properties[1]);
        $this->assertEquals(['bbbb', 'sdjf aksjdfks skd'], $properties[2]);

    }

    public function testMatchNameAndMethod()
    {

        list($name, $method) = StringUtils::matchNameAndMethod('abc.efg@ke_12');

        $this->assertEquals('abc.efg', $name);
        $this->assertEquals('ke_12', $method);

        $this->assertNull(StringUtils::matchNameAndMethod('abc@'));
        $this->assertNull(StringUtils::matchNameAndMethod('1abc@hhh'));
    }


}