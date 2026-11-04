<?php

use PHPUnit\Framework\TestCase;
use CodecDecodec\CodecDecodec;
use CodecDecodec\FF1\Enums\{Radix, Aes};

class FF1DecryptTest extends TestCase
{
    private string $secretKey16 = "2B7E151628AED2A6ABF7158809CF4F3C";

    private string $secretKey24 = "2B7E151628AED2A6ABF7158809CF4F3CEF4359D8D580AA4F";

    private string $secretKey32 = "2B7E151628AED2A6ABF7158809CF4F3CEF4359D8D580AA4F7F036D6F04FC6A94";

    public function testDecrypt()
    {
        $codec = new CodecDecodec();

        /* №1 */
        $this->assertSame('0123456789', $codec->ff1(
            key: hex2bin($this->secretKey16),
            tweak: '',
            aes: Aes::AES_128_CBC,
            radix: Radix::Decimal,
            length: 10
        )->decrypt("2433477484"));

        /* №2 */
        $this->assertSame('0123456789', $codec->ff1(
            key: hex2bin($this->secretKey16),
            tweak: hex2bin('39383736353433323130'),
            aes: Aes::AES_128_CBC,
            radix: Radix::Decimal,
            length: 10
        )->decrypt("6124200773"));

        /* №3 */
        $this->assertSame('0123456789abcdefghi', $codec->ff1(
            key: hex2bin($this->secretKey16),
            tweak: hex2bin('3737373770717273373737'),
            aes: Aes::AES_128_CBC,
            radix: Radix::AlphanumericLower,
            length: 19
        )->decrypt("a9tv40mll9kdu509eum"));

        /* №4 */
        $this->assertSame('0123456789', $codec->ff1(
            key: hex2bin($this->secretKey24),
            tweak: '',
            aes: Aes::AES_192_CBC,
            radix: Radix::Decimal,
            length: 10
        )->decrypt("2830668132"));

        /* №5 */
        $this->assertSame('0123456789', $codec->ff1(
            key: hex2bin($this->secretKey24),
            tweak: hex2bin('39383736353433323130'),
            aes: Aes::AES_192_CBC,
            radix: Radix::Decimal,
            length: 10
        )->decrypt("2496655549"));

        /* №6 */
        $this->assertSame('0123456789abcdefghi', $codec->ff1(
            key: hex2bin($this->secretKey24),
            tweak: hex2bin('3737373770717273373737'),
            aes: Aes::AES_192_CBC,
            radix: Radix::AlphanumericLower,
            length: 19
        )->decrypt("xbj3kv35jrawxv32ysr"));

        /* №7 */
        $this->assertSame('0123456789', $codec->ff1(
            key: hex2bin($this->secretKey32),
            tweak: '',
            aes: Aes::AES_256_CBC,
            radix: Radix::Decimal,
            length: 10
        )->decrypt("6657667009"));

        /* №8 */
        $this->assertSame('0123456789', $codec->ff1(
            key: hex2bin($this->secretKey32),
            tweak: hex2bin('39383736353433323130'),
            aes: Aes::AES_256_CBC,
            radix: Radix::Decimal,
            length: 10
        )->decrypt("1001623463"));

        /* №9 */
        $this->assertSame('0123456789abcdefghi', $codec->ff1(
            key: hex2bin($this->secretKey32),
            tweak: hex2bin('3737373770717273373737'),
            aes: Aes::AES_256_CBC,
            radix: Radix::AlphanumericLower,
            length: 19
        )->decrypt("xs8a0azh2avyalyzuwd"));
    }
}
