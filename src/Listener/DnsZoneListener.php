<?php namespace SuperV\Agents\PowerDns\Listener;

use Illuminate\Foundation\Bus\DispatchesJobs;
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
                'name' => $zone->domain,
                'type' => 'MASTER',
            ]
        );

        $zone->update(['external_id' => $id]);
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