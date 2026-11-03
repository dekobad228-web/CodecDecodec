<?php

declare(strict_types=1);

namespace CodecDecodec\FF1;

use CodecDecodec\Contracts\CodecInterface;
use CodecDecodec\FF1\Enums\{Aes, Radix};

class FF1 implements CodecInterface
{
    /**
     * @var Aes $aes Режим AES
     */
    private Aes $aes;
    /**
     * @var Radix $radix Алфавит и его размер
     */
    private Radix $radix;
    /**
     * @var int $length Максимальная длина шифруемого значения
     */
    private int $length;
    /**
     * @var string $key Секретный ключ
     */
    private string $key;
    /**
     * @var string $tweak Дополнительный контекст
     */
    private string $tweak;

    /**
     * @param string $key Секретный ключ
     * @param string $tweak Дополнительный контекст
     * @param Aes    $aes Режим AES
     * @param Radix  $radix Алфавит и его размер
     * @param int    $length Максимальная длина шифруемого значения
     */
    public function __construct(
        string $key,
        string $tweak,
        Aes $aes,
        Radix $radix,
        int $length,
    ) {
        $this->aes = $aes;
        $this->radix = $radix;
        $this->length = $length;
        $this->key = $key;
        $this->tweak = $tweak;
    }

    /**
     * Основной метод шифрования
     * 
     * @param string|int $x Данные, которые шифруем
     * @return string Зашифрованная строка
     */

    public function encrypt(string|int $x): string
    {
        return $this->encryptionRoundsFeistel($this->maxLength(strval($x)));
    }

    /**
     * Основной метод расшифровки
     * 
     * @param string $x Зашифрованная строка
     * @return string|int Возвращает исходное значение
     */

    public function decrypt(string $x): string
    {
        return $this->decryptionRoundsFeistel($x);
    }

    private function validateDecryptionStroke(string $x): string|int
    {

        return preg_match('/^\d+$/', $x) ? intval($x) : $x;
    }

    /**
     * Преобразование строки символов алфавита в число
     * 
     * @param string $x Входное число
     * @return int Полученное значение
     */
    protected function NUMradix(string $x): int
    {
        $result = 0;
        for ($i = 0, $n = strlen($x); $i < $n; $i++) {
            $result = $result * $this->radix->value + strpos($this->radix->alphabet(), $x[$i]);
        }
        return $result;
    }

    /**
     * Преобразование целого числа в строку цифр заданной длины и с заданным основанием системы счисления.
     * 
     * @param int $m Требуемая длина выходной строки
     * @param int $int Входное целое число
     * @return string Строка цифр
     */
    protected function STRm_radix(int $m, int $int): string
    {
        $result = [];
        for ($i = 1; $i <= $m; $i++) {
            $value = $int % $this->radix->value;
            $result[$m + 1 - $i] = $this->radix->alphabet()[$value];
            $int = intdiv($int, $this->radix->value);
        }
        return implode("", array_reverse($result));
    }

    /**
     * Метод псевдослучайного преобразования
     * 
     * @param string $x Входная строка
     * @return string Преобразованная строка
     */
    protected function PRF(string $x): string
    {
        $out = openssl_encrypt(
            $x,
            $this->aes->value,
            $this->key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            str_repeat("\0", 16)
        );

        return substr($out, -16);
    }

    /**
     * Проверяет, состоит ли строка исключительно из символов 0 и 1.
     *
     * @param string $string Проверяемая строка.
     * @return bool Возвращёт true в случае успеха
     */
    protected function isBitString(string $string): bool
    {
        return preg_match("/^[01]+$/", $string) === 1;
    }

    /**
     * Максимальная длина шифра
     * 
     * @param string $x Исходная строка
     * @return string Строка дополненная нулями до максимальной длины
     */
    private function maxLength(string $x): string
    {
        return str_pad($x, $this->length, "0", STR_PAD_LEFT);
    }


    /**
     * Длины строки и её половин
     * 
     * Длина строки (n)
     * Левая половина (A)
     * Правая половина (B)
     * 
     * @param string $x Исходное значение
     * @return array Возвращает массив длин.[n, A, B]
     */
    private function stringLength(string $x): array
    {
        $n = strlen($x);
        $lengthA = intdiv($n, 2);
        $lengthB = $n - $lengthA;

        return [$n, $lengthA, $lengthB];
    }

    /**
     * Получить значения левой и правой половины
     * 
     * @param string $x Входная строка
     * @param int $lengthA Длина левой половины
     * @param int $lengthB Длина правой половины
     * @return array [A, B]
     */
    private function halvesAsStrings(string $x, int $lengthA, int $lengthB): array
    {
        return [substr($x, 0, $lengthA), substr($x, $lengthA, $lengthB)];
    }

    /**
     * Переводит обе половины из строк в числа через Numradix
     * 
     * @param string $A Левая половина
     * @param string $B Правая половина
     * @return array [numA, numB]
     */
    private function halvesNumRadix(string $A, string $B): array
    {
        return [$this->NUMradix($A), $this->NUMradix($B)];
    }

    /**
     * Метод высчитывает, сколько различных значений может принимать каждая из половин
     * 
     * @param int $lengthA Длина левой половины
     * @param int $lengthB Длина правой половины
     * @return array [modA, modB]
     */
    private function halvesMaxLength(int $lengthA, int $lengthB): array
    {
        return [$this->radix->value ** $lengthA, $this->radix->value ** $lengthB];
    }

    /**
     * Сколько байтов потребуется, чтобы представить максимальное число, которое может содержаться во второй части B
     * 
     * @param int $lengthB Длина правой половины
     * @return int Количество байт
     */
    private function halfByteLength(int $lengthB): int
    {
        return intval(ceil(ceil($lengthB * log(floatval($this->radix->value), 2)) / 8));
    }

    /**
     * Промежуточная строка S
     * 
     * @param int $b Количество байт числа половины
     * @return int Длина S в байтах
     */
    private function prfBytesCount(int $b): int
    {
        return intval(4 * ceil($b / 4) + 4);
    }

    /**
     * 16 байт параметров, одинаковых для всех раундов.
     *
     * Состав:
     *  - 3 байта [1, 2, 1] (версия и тип шифра)
     *  - 3 байта radix
     *  - 1 байт число раундов (10)
     *  - 1 байт длина левой половины A по модулю 256
     *  - 4 байта длина строки n
     *  - 4 байта длина tweak
     *
     * @param int $n Длина всей строки
     * @param int $lengthA Длина левой половины
     * @return string Ровно 16 байт
     */
    private function headerBlock(int $n, int $lengthA): string
    {
        $version = pack('C3', 1, 2, 1);
        $radixBytes = substr(pack('N', $this->radix->value), 1, 3);
        $roundBytes = pack('C2', 10, $lengthA % 256);
        $nBytes = pack('N', $n);
        $tweakBytes = pack('N', strlen($this->tweak));
        return $version . $radixBytes . $roundBytes . $nBytes . $tweakBytes;
    }


    /**
     * Нулевое выравнивание O из стандарта.
     * 
     * Добирает нулевые байты, чтобы длина делилась на 16
     *
     * @param int $b Количество байт
     * @return string Нулевые байты
     */
    private function nullBytes(int $b): string
    {
        return str_repeat("\0", (16 - ((strlen($this->tweak) + intval($b) + 1) % 16)) % 16);
    }


    /**
     * Q из стандарта
     * 
     * @param string $O Нулевое выравние
     * @param int $i Номер раунда
     * @param int $num Число половины, от которой считается раунд
     * @param int $b Количество байт
     * @return string Строка 16 байт длиной
     */
    private function buildRoundInput(string $O, int $i, int $num, int $b): string
    {
        return $this->tweak . $O . pack('C', $i) . substr(pack('J', $num), -$b);
    }

    /**
     * y из стандарта
     * 
     * @param int $d Сколько байт брать из S
     * @param string $S Промежуточная строка S
     * @param int $mod 
     */
    private function bytesToNumberModulo(int $d, string $S, int $mod): int
    {
        $y = 0;

        for ($r = 0; $r < $d; $r++) {
            $y = ($y * 256 + ord($S[$r])) % $mod;
        }

        return $y;
    }

    /**
     * 10 раундов сети Фейстеля
     * 
     * @param string $x Исходное значение увеличенное до максималоной длины
     * @return string Зашифрованный текст
     */
    private function encryptionRoundsFeistel(string $x): string
    {

        [$n, $lengthA, $lengthB] = $this->stringLength($x);

        [$A, $B] = $this->halvesAsStrings($x, $lengthA, $lengthB);

        [$numA, $numB] = $this->halvesNumRadix($A, $B);

        [$modA, $modB] = $this->halvesMaxLength($lengthA, $lengthB);
        $b = $this->halfByteLength($lengthB);
        $d = $this->prfBytesCount($b);
        $P = $this->headerBlock($n, $lengthA);
        $O = $this->nullBytes($b);

        for ($i = 0; $i < 10; $i++) {
            $Q = $this->buildRoundInput($O, $i, $numB, $b);
            $R = $this->PRF($P . $Q);
            $S = substr($R, 0, $d);
            $mod = ($i % 2 === 0) ? $modA : $modB;
            $y = $this->bytesToNumberModulo($d, $S, $mod);
            $c = ($numA + $y) % $mod;
            $numA = $numB;
            $numB = $c;
        }

        return $this->STRm_radix($lengthA, $numA) . $this->STRm_radix($lengthB, $numB);
    }

    /**
     * 10 раундом Фейстеля в обратном порядке
     * 
     * @param string $x Шифртекст
     * @return string Возвращет исходное значение в виде строки с добавленными до максимальной длины нулями
     */
    public function decryptionRoundsFeistel(string $x): string
    {
        [$n, $lengthA, $lengthB] = $this->stringLength($x);

        [$A, $B] = $this->halvesAsStrings($x, $lengthA, $lengthB);

        [$numA, $numB] = $this->halvesNumRadix($A, $B);

        [$modA, $modB] = $this->halvesMaxLength($lengthA, $lengthB);
        $b = $this->halfByteLength($lengthB);
        $d = $this->prfBytesCount($b);
        $P = $this->headerBlock($n, $lengthA);
        $O = $this->nullBytes($b);

        for ($i = 9; $i >= 0; $i--) {
            $Q = $this->buildRoundInput($O, $i, $numA, $b);
            $R = $this->PRF($P . $Q);
            $S = substr($R, 0, $d);
            $mod = ($i % 2 === 0) ? $modA : $modB;
            $y = $this->bytesToNumberModulo($d, $S, $mod);
            $c = ((($numB - $y) % $mod + $mod) % $mod);
            $numB = $numA;
            $numA = $c;
        }

        return $this->STRm_radix($lengthA, $numA) . $this->STRm_radix($lengthB, $numB);
    }
}
