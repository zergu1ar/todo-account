<?php
/**
 * Created by PhpStorm.
 * User: devel
 * Date: 05.07.17
 * Time: 11:14
 */

namespace Tests\Todo\Crypt;

use Todo\Crypt\Coder;

class CoderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $string
     * @param int $length
     *
     * @dataProvider dataProvider
     */
    public function testCreateToken($string, $length)
    {
        $result = Coder::createToken($string);
        $this->assertEquals($length, strlen($result));
        $this->assertNotEquals($string, $result);
    }

    /**
     * @param string $string
     * @param int $length
     *
     * @dataProvider dataProvider
     */
    public function testEncrypt($string, $length)
    {
        $result = Coder::encrypt($string);
        $this->assertEquals($length, strlen($result));
        $this->assertNotEquals($string, $result);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['test string', 40],
            ['Some another string', 40],
            ['small', 40],
            ['There is the biggest length string of this test, but in results we have only 40 symbols length hash', 40]
        ];
    }
}