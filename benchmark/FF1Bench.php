<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CodecDecodec\FF1\FF1;
use CodecDecodec\FF1\Enums\Aes;
use CodecDecodec\FF1\Enums\Radix;

class FF1Bench
{
    private FF1 $ff1;
    private string $x = '1234567890123456';
    private string $encrypted;

    public function setUp(): void
    {
        $this->ff1 = new FF1(
            key: '0123456789abcdef',
            tweak: '12345678',
            aes: Aes::AES_128_CBC,
            radix: Radix::Decimal,
            length: 16,
        );

        $this->encrypted = $this->ff1->encrypt($this->x);
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Revs(100)
     * @Iterations(5)
     */
    public function benchEncrypt(): void
    {
        $this->ff1->encrypt($this->x);
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Revs(100)
     * @Iterations(5)
     */
    public function benchDecrypt(): void
    {
        $this->ff1->decrypt($this->encrypted);
    }
}
