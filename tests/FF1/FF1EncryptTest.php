<?php

use PHPUnit\Framework\TestCase;
use CodecDecodec\CodecDecodec;
use CodecDecodec\FF1\Enums\{Radix, Aes};

class FF1EncryptTest extends TestCase
{
    private string $secretKey16 = "2B7E151628AED2A6ABF7158809CF4F3C";

    private string $secretKey24 = "2B7E151628AED2A6ABF7158809CF4F3CEF4359D8D580AA4F";

    private string $secretKey32 = "2B7E151628AED2A6ABF7158809CF4F3CEF4359D8D580AA4F7F036D6F04FC6A94";

    public function testEncrypt()
    {
        $codec = new CodecDecodec();

        /* №1 */
        $this->assertSame('2433477484', $codec->ff1(
            key: hex2bin($this->secretKey16),
            tweak: '',
            aes: Aes::AES_128_CBC,
            radix: Radix::Decimal,
            length: 10
        )->encrypt("0123456789"));

        /* №2 */
        $this->assertSame('6124200773', $codec->ff1(
            key: hex2bin($this->secretKey16),
            tweak: hex2bin('39383736353433323130'),
            aes: Aes::AES_128_CBC,
            radix: Radix::Decimal,
            length: 10
        )->encrypt("0123456789"));

        /* №3 */
        $this->assertSame('a9tv40mll9kdu509eum', $codec->ff1(
            key: hex2bin($this->secretKey16),
            tweak: hex2bin('3737373770717273373737'),
            aes: Aes::AES_128_CBC,
            radix: Radix::AlphanumericLower,
            length: 19
        )->encrypt("0123456789abcdefghi"));

        /* №4 */
        $this->assertSame('2830668132', $codec->ff1(
            key: hex2bin($this->secretKey24),
            tweak: '',
            aes: Aes::AES_192_CBC,
            radix: Radix::Decimal,
            length: 10
        )->encrypt("0123456789"));

        /* №5 */
        $this->assertSame('2496655549', $codec->ff1(
            key: hex2bin($this->secretKey24),
            tweak: hex2bin('39383736353433323130'),
            aes: Aes::AES_192_CBC,
            radix: Radix::Decimal,
            length: 10
        )->encrypt("0123456789"));

        /* №6 */
        $this->assertSame('xbj3kv35jrawxv32ysr', $codec->ff1(
            key: hex2bin($this->secretKey24),
            tweak: hex2bin('3737373770717273373737'),
            aes: Aes::AES_192_CBC,
            radix: Radix::AlphanumericLower,
            length: 19
        )->encrypt("0123456789abcdefghi"));

        /* №7 */
        $this->assertSame('6657667009', $codec->ff1(
            key: hex2bin($this->secretKey32),
            tweak: '',
            aes: Aes::AES_256_CBC,
            radix: Radix::Decimal,
            length: 10
        )->encrypt("0123456789"));

        /* №8 */
        $this->assertSame('1001623463', $codec->ff1(
            key: hex2bin($this->secretKey32),
            tweak: hex2bin('39383736353433323130'),
            aes: Aes::AES_256_CBC,
            radix: Radix::Decimal,
            length: 10
        )->encrypt("0123456789"));

        /* №9 */
        $this->assertSame('xs8a0azh2avyalyzuwd', $codec->ff1(
            key: hex2bin($this->secretKey32),
            tweak: hex2bin('3737373770717273373737'),
            aes: Aes::AES_256_CBC,
            radix: Radix::AlphanumericLower,
            length: 19
        )->encrypt("0123456789abcdefghi"));
    }
}
