<?php namespace SuperV\Agents\PowerDns;

use SuperV\Agents\PowerDns\Listener\ZoneCreatedListener;
use SuperV\Platform\Domains\Droplet\DropletServiceProvider;

class PowerDnsAgentServiceProvider extends DropletServiceProvider
{
    protected $listeners = [
        'dns_zone.created'   => ZoneCreatedListener::class,
        'dns_zone.updated'   => '',
        'dns_zone.deleted'   => '',
        'dns.record.created' => '',
        'dns.record.updated' => '',
        'dns.record.deleted' => '',

    ];
}