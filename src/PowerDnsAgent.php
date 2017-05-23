<?php namespace SuperV\Agents\PowerDns;

use SuperV\Platform\Domains\Droplet\Droplet;

class PowerDnsAgent extends Droplet
{
    protected $commands = [
        'install' => 'SuperV\Agents\PowerDns\Command\InstallPowerDns'
    ];
}