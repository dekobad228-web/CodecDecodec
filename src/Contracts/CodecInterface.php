<?php

namespace CodecDecodec\Contracts;

interface CodecInterface
{
    /**
     * Метод шифрования
     * 
     * @param string|int $x Входное значение для шифрования
     */
    public function encrypt(string|int $x): string;

    /**
     * Метод дешифрования
     * 
     * @param string $x Входное значение для дешифрования
     */
    public function decrypt(string $x): string;
}
