<?php

namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class WorkerManCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerManServer {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '启动workerMan服务';

    /**
     * php-cli 执行时，自动调用此函数
     * Execute the console command.
     */
    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'wk';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    /**
     * 启动workerMan相关服务
     */
    public function start()
    {
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();
        Worker::runAll();
    }

    private function startBusinessWorker()
    {
        $worker                  = new BusinessWorker();
        $worker->name            = config('GatewayWorker.businessWorker.name');
        $worker->count           = config('GatewayWorker.businessWorker.count');
        $worker->eventHandler    = config('GatewayWorker.businessWorker.eventHandler');
        $worker->registerAddress = config('GatewayWorker.registerAddress');
    }

    private function startGateWay()
    {
        $gateway = new Gateway(config('GatewayWorker.gateway.websocket'));
        $gateway->name                 = config('GatewayWorker.gateway.name');
        $gateway->count                = config('GatewayWorker.gateway.count');
        $gateway->lanIp                = config('GatewayWorker.gateway.lanIp');
        $gateway->startPort            = config('GatewayWorker.gateway.port');
        $gateway->pingInterval         = config('GatewayWorker.gateway.pingInterval');
        $gateway->pingNotResponseLimit = config('GatewayWorker.gateway.pingNotResponseLimit');
        $gateway->pingData             = config('GatewayWorker.gateway.pingData');
        $gateway->registerAddress      = config('GatewayWorker.registerAddress');
    }

    private function startRegister()
    {
        new Register('text://0.0.0.0:1236');
    }
}
