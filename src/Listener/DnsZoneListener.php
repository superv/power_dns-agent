<?php namespace SuperV\Agents\PowerDns\Listener;

use Illuminate\Foundation\Bus\DispatchesJobs;
use SuperV\Agents\PowerDns\Command\GetConnection;
use SuperV\Modules\Hosting\Domains\Services\Dns\ZoneModel;

class DnsZoneListener
{
    use DispatchesJobs;

    public function created(ZoneModel $zone)
    {
        $connection = $this->dispatch(new GetConnection($zone->getServer()));

        $id = $connection->table('domains')->insertGetId(
            [
                'name' => $zone->domain,
                'type' => 'MASTER',
            ]
        );

        $zone->update(['external_id' => $id]);
    }

    public function updated(DnsZoneModel $zone)
    {
    }

    public function deleted(DnsZoneModel $zone)
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