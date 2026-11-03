<?php

namespace CodecDecodec\Contracts;

use UnitEnum;

interface ValidatorInterface
{

    /**
     * Метод сверки данных входа с текущим перечислением
     * 
     * @param string $x Входная строка
     * @param UnitEnum $enum Значение класса перечисления
     */
    public static function validate(string $x, UnitEnum $enum): bool;
}
