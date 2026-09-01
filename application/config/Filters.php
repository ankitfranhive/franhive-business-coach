<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public array $aliases = [
        'ctsecret' => \App\Filters\CloudTalkSecret::class,
    ];

    public array $globals = [
        'before' => [],
        'after'  => [],
    ];

    public array $methods = [];
    public array $filters = [];
}
