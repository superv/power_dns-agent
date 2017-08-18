<?php namespace SuperV\Agents\PowerDns;

use SuperV\Agents\PowerDns\Command\InstallPowerDns;
use SuperV\Agents\PowerDns\Command\RemovePowerDns;
use SuperV\Platform\Domains\Droplet\Agent\Agent;

class PowerDnsAgent extends Agent
{
    protected $features = [
        'install' => InstallPowerDns::class,
        'remove'  => RemovePowerDns::class,
    ];
}