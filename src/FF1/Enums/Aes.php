<?php

namespace CodecDecodec\FF1\Enums;

enum Aes: string
{
    case AES_128_CBC = 'aes-128-cbc';
    case AES_192_CBC = 'aes-192-cbc';
    case AES_256_CBC = 'aes-256-cbc';
}
