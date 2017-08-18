<?php namespace SuperV\Agents\PowerDns\Command;

use SuperV\Modules\Supreme\Domains\Server\Jobs\RunServerScriptJob;
use SuperV\Platform\Domains\Droplet\Agent\AgentFeature;

class InstallPowerDns extends AgentFeature
{
    protected $jobs = [];

    protected $mysqlAdminPass = 'admin_shh';

    public function handle()
    {
        if (!$server = $this->server()) {
            throw new \InvalidArgumentException('Can not find server in feature params');
        }
        // update server packages
        $script = 'apt-get update';
        $this->addJob((new RunServerScriptJob($server, 'Update Server Packages'))->setScript($script));

        // install mysql
        $stub = "superv.agents.power_dns::install_mysql";
        $tokens = ['mysql_admin_pass' => $this->mysqlAdminPass];
        $this->addJob((new RunServerScriptJob($server, 'Install Mysql Server'))->fromStub($stub, $tokens));

        // restart mysql server
        $this->addJob((new RunServerScriptJob($server, 'Restart Mysql Server'))->setScript("service mysql restart"));

        // install power dns
        $stub = "superv.agents.power_dns::install_power_dns";
        $tokens = [
            'mysql_admin_pass' => $this->mysqlAdminPass,
            'mysql_app_pass'   => 'app_shh',
        ];
        $this->addJob((new RunServerScriptJob($server, 'Install Power DNS'))->fromStub($stub, $tokens));

        return $this->jobs;
    }
}