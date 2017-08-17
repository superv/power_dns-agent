<?php namespace SuperV\Agents\PowerDns\Command;

use SuperV\Platform\Domains\Droplet\Agent\AgentFeature;

class InstallPowerDns extends AgentFeature
{
    public function handle()
    {
        $this->server->cmd('apt-get update');

        // install mysql
        $mysqlAdminPass = 'admin_shh';
        $script = $this->stub('install_mysql', ['mysql_admin_pass' => $mysqlAdminPass]);
        $this->server->cmd($script);

        // restart mysql server
        $this->server->restart('mysql');

        // instal power dns
        $script = $this->stub('install_power_dns',
            [
                'mysql_admin_pass' => $mysqlAdminPass,
                'mysql_app_pass'   => 'app_shh',
            ]
        );
        $this->server->cmd($script);
    }
}