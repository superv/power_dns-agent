<?php namespace SuperV\Agents\PowerDns\Listener;

use Illuminate\Foundation\Bus\DispatchesJobs;
use SuperV\Agents\PowerDns\Command\GetConnection;
use SuperV\Modules\Hosting\Domains\Services\Dns\DnsZoneModel;

class ZoneCreatedListener
{
    use DispatchesJobs;

    public function handle(DnsZoneModel $zone)
    {
        $connection = $this->dispatch(new GetConnection());

        $id = $connection->table('domains')->insertGetId(
            [
                'name' => $zone->domain,
                'type' => 'MASTER',
            ]
        );

        $zone->update(['external_id' => $id]);
    }
}