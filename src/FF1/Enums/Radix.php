<?php

namespace CodecDecodec\FF1\Enums;


/**
 * Количество уникальных символов в алфавите, 
 * используемом алгоритмом FF1 для представления и шифрования данных
 */
enum Radix: int
{
    /** Двоичная система счисления: 0 и 1 */
    case Binary = 2;

    /** 
     * Десятичная система счисления: цифры от 0 до 9.
     * Используется по умолчанию
     */
    case Decimal = 10;

    /** Шестнадцатеричная система счисления: цифры от 0 до 9 и буквы A–F */
    case Hexadecimal = 16;

    /** Алфавит из строчных латинских букв в нижнем регистре a–z*/
    case LatinLower = 26;

    /** Алфавит из цифр и строчных латинских букв: 0–9 и a–z */
    case AlphanumericLower = 36;

    /** Алфавит из цифр, строчных и заглавных латинских букв: 0–9, a–z, A–Z */
    case AlphanumericFull = 62;

    public function alphabet(): string
    {
        return match ($this) {
            self::Binary => '01',
            self::Decimal => '0123456789',
            self::Hexadecimal => '0123456789abcdef',
            self::LatinLower => 'abcdefghijklmnopqrstuvwxyz',
            self::AlphanumericLower => '0123456789abcdefghijklmnopqrstuvwxyz',
            self::AlphanumericFull => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        };
    }
}
