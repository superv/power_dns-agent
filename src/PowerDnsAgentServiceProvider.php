<?php namespace SuperV\Agents\PowerDns;

use SuperV\Agents\PowerDns\Listener\DnsRecordListener;
use SuperV\Agents\PowerDns\Listener\DnsZoneListener;
use SuperV\Platform\Domains\Droplet\DropletServiceProvider;

class PowerDnsAgentServiceProvider extends DropletServiceProvider
{
    protected $listeners = [
        'zone.created'   => DnsZoneListener::class . '@created',
        'zone.updated'   => DnsZoneListener::class . '@updated',
        'zone.deleted'   => DnsZoneListener::class . '@deleted',
        'record.created' => DnsRecordListener::class . '@created',
        'record.updated' => DnsRecordListener::class . '@updated',
        'record.deleted' => DnsRecordListener::class . '@deleted',
    ];
}