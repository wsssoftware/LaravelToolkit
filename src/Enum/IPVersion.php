<?php

namespace LaravelToolkit\Enum;

enum IPVersion: string
{
    case IPV4 = 'IPv4';
    case IPV6 = 'IPv6';
    case ALL = 'all';
}
