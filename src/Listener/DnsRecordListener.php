<?php namespace SuperV\Agents\PowerDns\Listener;

use Illuminate\Foundation\Bus\DispatchesJobs;
use SuperV\Agents\PowerDns\Command\GetConnection;
use SuperV\Modules\Hosting\Domains\Services\Dns\RecordModel;
use SuperV\Modules\Hosting\Domains\Services\Dns\ZoneModel;
use SuperV\Platform\Domains\Event\Listener;

class DnsRecordListener extends Listener
{
    public function created(RecordModel $record)
    {
        /** @var ZoneModel $zone */
        $zone = $record->zone;

        $connection = $this->dispatch(new GetConnection($zone->getServer()));
        $domainId = $connection->table('domains')->select('id')->where('name', $zone->getDomain())->first()->id;

        $id = $connection->table('records')->insertGetId([
            'domain_id'   => $domainId,
            'name'        => $record->name,
            'type'        => $record->type,
            'content'     => $record->content,
            'ttl'         => $record->ttl,
            'prio'        => $record->prio,
            'change_date' => time(),
        ]);

        $record->update(['external_id' => $id]);
    }

    public function updated(RecordModel $record)
    {
        /** @var ZoneModel $zone */
        $zone = $record->zone;

        $connection = $this->dispatch(new GetConnection($zone->getServer()));
        $connection->table('records')
                   ->where('id', $record->external_id)
                   ->update(
                       [
                           'name'        => $record->name,
                           'type'        => $record->type,
                           'content'     => $record->content,
                           'ttl'         => $record->ttl,
                           'prio'        => $record->prio,
                           'change_date' => time(),
                       ]
                   );
    }

    public function deleted(RecordModel $record)
    {
        /** @var ZoneModel $zone */
        $zone = $record->zone;

        $connection = $this->dispatch(new GetConnection($zone->getServer()));
        $connection->table('records')
                   ->where('id', $record->external_id)
                   ->delete();
    }
}