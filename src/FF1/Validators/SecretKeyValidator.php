<?php

namespace CodecDecodec\FF1\Validators;

use CodecDecodec\FF1\Enums\Aes;
use CodecDecodec\Contracts\ValidatorInterface;
use Exception;
use UnitEnum;

final class SecretKeyValidator implements ValidatorInterface
{

    /**
     * Проверка длины секретного ключа
     * 
     * @param string $key Секретный ключ
     * @param UnitEnum $enum Класс Aes
     * @return bool  
     */
    public static function validate(string $key, UnitEnum $enum): bool
    {
        if ($enum === Aes::AES_128_CBC && strlen($key) !== 16) {
            throw new Exception("Для этого типа AES секретный ключ должен быть длиной 16 байт");
        }

        if ($enum === Aes::AES_192_CBC && strlen($key) !== 24) {
            throw new Exception("Для этого типа AES секретный ключ должен быть длиной 24 байт");
        }

        if ($enum === Aes::AES_256_CBC && strlen($key) !== 32) {
            throw new Exception("Для этого типа AES секретный ключ должен быть длиной 32 байт");
        }

        return true;
    }
}
