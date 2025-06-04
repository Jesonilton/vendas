<?php

namespace App\Console\Commands;

use App\Services\SaleService;
use Illuminate\Console\Command;

class SendManagerDailySaleReport extends Command
{
    public function __construct(protected SaleService $saleService)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-manager-daily-sale-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sale report to manager';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->saleService->sendManagerDailySaleReportEmail();
    }
}
