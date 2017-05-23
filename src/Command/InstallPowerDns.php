<?php namespace SuperV\Agents\PowerDns\Command;

use SuperV\Modules\Supreme\Domains\Script\Command\ParseFile;
use SuperV\Modules\Supreme\Domains\Server\Formula;
use SuperV\Modules\Supreme\Domains\Server\Server;
use SuperV\Platform\Domains\Droplet\Jobs\LocateResourceJob;
use SuperV\Platform\Domains\Feature\JobDispatcherTrait;

class InstallPowerDns
{
    use JobDispatcherTrait;

    /**
     * @var array
     */
    private $params;

    /**
     * @var \SuperV\Modules\Supreme\Domains\Server\Server
     */
    private $server;

    public function __construct(Server $server, array $params = [])
    {
        $this->params = $params;
        $this->server = $server;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return array_get($this->params, $name);
    }

    public function handle()
    {
        // apt update
        $this->server->cmd('apt-get update');

        // install mysql
        $mysqlAdminPass = 'admin_shh';
        $script = $this->stub('install_mysql', ['mysql_admin_pass' => $mysqlAdminPass]);
        $this->server->cmd($script);

        // instal power dns
        $script = $this->stub('install_power_dns',
            [
                'mysql_admin_pass' => $mysqlAdminPass,
                'mysql_app_pass'   => 'app_shh',
            ]
        );
        $this->server->cmd($script);

        if (!$this->server->success()) {
            throw new \Exception($this->server->output());
        }

        return true;
    }

    protected function stub($stub, $tokens)
    {
        $location = $this->run(new LocateResourceJob("superv.agents.power_dns::{$stub}", 'stub'));

        return $this->run(new ParseFile($location, $tokens));
    }
}