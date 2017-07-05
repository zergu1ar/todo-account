<?php
/**
 * Created by PhpStorm.
 * User: devel
 * Date: 05.07.17
 * Time: 11:56
 */

namespace Tests\Todo\Validator;

use Todo\Validator\Checker;

class CheckerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Checker */
    private $checker;

    public function setUp()
    {
        $this->checker = new Checker;
    }

    /**
     * @param string $string
     * @param int $minLength
     * @param int $maxLength
     * @param array $expectedError
     *
     * @dataProvider dataSuccessProvider
     */
    public function testValidateLogin($string, $minLength, $maxLength, $expectedError)
    {
        $res = $this->checker->validateString($string, $minLength, $maxLength);
        $this->assertEquals($res, $expectedError);
    }

    /**
     * @param string $string
     * @param int $minLength
     * @param int $maxLength
     * @param string $expectedError
     *
     * @dataProvider dataEmptyProvider
     */
    public function testValidateEmptyLogin($string, $minLength, $maxLength, $expectedError)
    {
        $res = $this->checker->validateString($string, $minLength, $maxLength);
        $this->assertContains($expectedError, $res);
    }

    /**
     * @param string $string
     * @param int $minLength
     * @param int $maxLength
     * @param string $expectedError
     *
     * @dataProvider dataLengthProvider
     */
    public function testValidateLengthLogin($string, $minLength, $maxLength, $expectedError)
    {
        $res = $this->checker->validateString($string, $minLength, $maxLength);
        $this->assertContains($expectedError, $res);
    }

    /**
     * @return array
     */
    public function dataSuccessProvider()
    {
        return [
            ['test', 3, 6, []],
            ['test', 1, 4, []],
            ['test case so long', 3, 60, []]
        ];
    }

    /**
     * @return array
     */
    public function dataLengthProvider()
    {
        return [
            ['test case', 1, 5, 'Field must be from 1 to 5 symbols'],
            ['1', 2, 5, 'Field must be from 2 to 5 symbols'],
            ['Check string so long', 1, 5, 'Field must be from 1 to 5 symbols']
        ];
    }

    /**
     * @return array
     */
    public function dataEmptyProvider()
    {
        return [
            ['', 1, 5, 'Field can not be empty'],
            [' ', 2, 5, 'Field can not be empty']
        ];
    }
}