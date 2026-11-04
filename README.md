# Codec-Decodec

**Codec-Decodec** — PHP-библиотека для шифрования и расшифрования данных с использованием симметричных криптографических алгоритмов.

Библиотека предоставляет единый интерфейс для работы с различными алгоритмами шифрования, скрывая детали их внутренней реализации.

> **Примечание:** Библиотека создаётся больше в учебных целях, а также в целях проверки навыков и из чистого любопытства. Использовать на свой страх и риск

## Возможности

- Format-Preserving Encryption (FPE) на основе алгоритма FF1.
- Поддержка AES-128, AES-192 и AES-256.
- Настройка основания системы счисления (radix).
- Поддержка дополнительного параметра Tweak.
- Валидация входных данных.
- Реализация операций FF1 преимущественно на чистом PHP.

## Требования

- PHP 8.1 или выше.
- Расширение PHP OpenSSL.

## Установка

Установите библиотеку с помощью Composer:

```bash
composer require deko/codec-decodec
```

## Использование

### Шифрование с методом create

```php
use CodecDecodec\CodecDecodec;
use CodecDecodec\FF1\Enums\Aes;
use CodecDecodec\FF1\Enums\Radix;

$key = random_bytes(32);

$codec = new CodecDecodec();

$ff1 = $codec->create('ff1', [
    'key'   => $key,
    'tweak' => '',
    'aes'   => Aes::AES_256_CBC,
    'radix' => Radix::Decimal,
]);

$encrypted = $ff1->encrypt('123456789012');
$decrypted = $ff1->decrypt($encrypted);
```
### Второй вариант шифрования

```php
use CodecDecodec\CodecDecodec;
use CodecDecodec\FF1\Enums\Aes;
use CodecDecodec\FF1\Enums\Radix;

$key = random_bytes(32);

$codec = new CodecDecodec();

$ff1 = $codec->ff1(
    key: $key,
    tweak: '',
    aes: Aes::AES_256_CBC,
    radix: Radix::Decimal,
);

$encrypted = $codec->encrypt('123456789012');
$decrypted = $codec->decrypt($encrypted);
```

После расшифрования будет получено исходное значение.

### Параметры

| Параметр | Описание |
|---|---|
| `key` | Секретный ключ шифрования. Длина должна соответствовать выбранному варианту AES. |
| `aes` | Вариант алгоритма AES. |
| `radix` | Основание системы счисления, используемой для представления входных данных. |
| `tweak` | Необязательное бинарное значение, используемое как дополнительный параметр шифрования. |

### Поддерживаемые варианты AES

| Алгоритм | Длина ключа |
|---|---:|
| AES-128 | 16 байт |
| AES-192 | 24 байта |
| AES-256 | 32 байта |

### Поддерживаемые системы счисления (Radix)

Библиотека поддерживает следующие системы счисления:

| Система счисления | Radix | Используемые символы |
|---|---:|---|
| Binary | 2 | `0–1` |
| Decimal | 10 | `0–9` |
| Hexadecimal | 16 | `0–9`, `a–f` |
| LatinLower | 26 | `a–z` |
| AlphanumericLower | 36 | `0–9`, `a–z` |
| AlphanumericFull | 62 | `0–9`, `a–z`, `A–Z` |

Выбор системы счисления определяет допустимый алфавит входных данных и формат результата шифрования.

## Лицензия

Проект распространяется под лицензией MIT. Подробности приведены в файле [LICENSE](LICENSE.md).

## Автор

deko — [GitHub](https://github.com/dekobad228-web)