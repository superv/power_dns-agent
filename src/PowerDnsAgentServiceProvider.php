<?php namespace SuperV\Agents\PowerDns;

use SuperV\Agents\PowerDns\Listener\DnsRecordListener;
use SuperV\Agents\PowerDns\Listener\DnsZoneListener;
use SuperV\Platform\Domains\Droplet\DropletServiceProvider;

class PowerDnsAgentServiceProvider extends DropletServiceProvider
{
    protected $listeners = [
        'dns_zone.created'   => DnsZoneListener::class . '@created',
        'dns_zone.updated'   => DnsZoneListener::class . '@updated',
        'dns_zone.deleted'   => DnsZoneListener::class . '@deleted',
        'dns_record.created' => DnsRecordListener::class . '@created',
        'dns_record.updated' => DnsRecordListener::class . '@updated',
        'dns_record.deleted' => DnsRecordListener::class . '@deleted',
    ];
}