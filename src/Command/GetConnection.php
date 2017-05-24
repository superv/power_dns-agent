<?php namespace SuperV\Agents\PowerDns\Command;

use Illuminate\Config\Repository;
use SuperV\Modules\Supreme\Domains\Server\Model\ServerModel;

class GetConnection
{
    /**
     * @var \SuperV\Modules\Supreme\Domains\Server\Model\ServerModel
     */
    private $server;

    public function __construct(ServerModel $server)
    {
        $this->server = $server;
    }

    public function handle(Repository $config)
    {
        $config->set('database.connections.powerdns', [
            'driver'    => 'mysql',
            'host'      => $this->server->ip(),
            'database'  => 'pdns',
            'username'  => 'pdns',
            'password'  => 'app_shh',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict'    => false,
        ]);

        return \DB::connection('powerdns');
    }
}