<?php namespace SuperV\Agents\PowerDns\Command;

use SuperV\Platform\Domains\Droplet\Agent\AgentFeature;

class InstallPowerDns extends AgentFeature
{
    protected $jobs = [];

    protected $mysqlAdminPass = 'admin_shh';

    public function handle()
    {
        if (! $server = $this->server()) {
            throw new \InvalidArgumentException('Can not find server in feature params');
        }

        $this->job('Update Server Package')
             ->script('apt-get update');

        $this->job('Install Mysql Server')
             ->stub('superv.agents.power_dns::install_mysql', ['mysql_admin_pass' => $this->mysqlAdminPass]);

        $this->job('Restart Mysql Server')
             ->script('service mysql restart');

        $this->job('Install Power DNS')
             ->stub('superv.agents.power_dns::install_power_dns', [
                 'mysql_admin_pass' => $this->mysqlAdminPass,
                 'mysql_app_pass'   => 'app_shh',
             ]);

        return $this->jobs;
    }
}