<?php namespace SuperV\Agents\PowerDns\Command;

use SuperV\Platform\Domains\Droplet\Agent\AgentFeature;

class RemovePowerDns extends AgentFeature
{
    public function handle()
    {
        $this->server->cmd('echo "command2"');
    }
}