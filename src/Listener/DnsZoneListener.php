<?php namespace SuperV\Agents\PowerDns\Listener;

use SuperV\Agents\PowerDns\Command\GetConnection;
use SuperV\Modules\Hosting\Domains\Services\Dns\ZoneModel;
use SuperV\Platform\Domains\Event\Listener;

class DnsZoneListener extends Listener
{
    public function created(ZoneModel $zone)
    {
        $connection = $this->dispatch(new GetConnection($zone->getServer()));

        $id = $connection->table('domains')->insertGetId(
            [
                'name' => $zone->getDomain(),
                'type' => 'MASTER',
            ]
        );

        $zone->update(['external_id' => $id]);

        $zone->records()->create([
            'name'    => $zone->getDomain(),
            'type'    => 'SOA',
            'content' => 'ns1.superv.io hostmaster.superv.io ' . date('YmdH') . ' 3600 7200 604800 3600',
            'ttl'     => 3600,
            'prio'    => 0,
        ]);
    }

    public function updated(ZoneModel $zone)
    {
    }

    public function deleted(ZoneModel $zone)
    {
        $connection = $this->dispatch(new GetConnection($zone->getServer()));

        $domain = $connection->table('domains')
                             ->select('id')
                             ->where('name', $zone->domain)
                             ->first();

        if ($domain) {
            $connection->table('records')->where('domain_id', $domain->id)->delete();
            $connection->table('domains')->where('name', $zone->domain)->delete();
        }
    }
}