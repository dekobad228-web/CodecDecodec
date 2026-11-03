<?php

namespace CodecDecodec;

use CodecDecodec\Contracts\CodecInterface;
use CodecDecodec\FF1\FF1;
use CodecDecodec\FF1\Enums\{Aes, Radix};
use CodecDecodec\FF1\Validators\SecretKeyValidator;
use Exception;

class CodecDecodec
{
    /**
     * Создание шифровальщика
     * 
     * @param string $algorithm Выбрать алгоритм шифрования
     * @param array $params Параметры шифрования. Если не указывать, то будут параметры по умолчанию
     * @return CodecInterface Объект с методами шифрования encrypt() и decrypt()
     */
    public function create(string $algorithm, array $params = []): CodecInterface
    {
        return match ($algorithm) {
            'ff1' => $this->ff1(...$params),
            default => throw new Exception("Неизвестный алгоритм")
        };
    }

    /**
     * Метод создаёт объект шифрования на основе алгоритма FF1
     * 
     * @param string $key Секретный ключ
     * @param string $tweak Дополнительный контекст шифрования
     * @param Aes $aes Выбор AES-шифрования (размера ключа). По умолчанию 128 бит
     * @param Radix $radix Выбор количества уникальных символов в алфавите. По умолчанию 10 (от 0 до 9)
     * @param int $length Максимальная длина шифра. По умолчанию 12
     * @return CodecInterface Объект с методами шифрования encrypt() и decrypt()
     */
    public function ff1(string $key, string $tweak = '', Aes $aes = Aes::AES_128_CBC, Radix $radix = Radix::Decimal, int $length = 12): CodecInterface
    {
        SecretKeyValidator::validate($key, $aes);

        return new FF1(
            key: $key,
            tweak: $tweak,
            aes: $aes,
            radix: $radix,
            length: $length
        );
    }
}
