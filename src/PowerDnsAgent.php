<?php namespace SuperV\Agents\PowerDns;

use SuperV\Agents\PowerDns\Command\InstallPowerDns;
use SuperV\Platform\Domains\Droplet\Droplet;

class PowerDnsAgent extends Droplet
{
    protected $commands = [
        'install' => InstallPowerDns::class,
    ];
}