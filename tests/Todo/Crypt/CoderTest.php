<?php

namespace Todo\Tests\Crypt;

use Todo\Crypt\Coder;

/**
 * Class CoderTest
 * @package Todo\Tests\Crypt
 */
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
