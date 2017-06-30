<?php namespace SuperV\Agents\PowerDns;

use SuperV\Agents\PowerDns\Listener\DnsRecordListener;
use SuperV\Agents\PowerDns\Listener\DnsZoneListener;
use SuperV\Platform\Domains\Droplet\DropletServiceProvider;

class PowerDnsAgentServiceProvider extends DropletServiceProvider
{
    protected $listeners = [
        'zone.*'   => DnsZoneListener::class,
        'record.*' => DnsRecordListener::class,
    ];
}