<?php namespace SuperV\Agents\PowerDns\Command;

use Illuminate\Config\Repository;

class GetConnection
{
    public function handle(Repository $config)
    {
        $config->set('database.connections.powerdns', [
            'driver'    => 'mysql',
            'host'      => '192.168.5.10',
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