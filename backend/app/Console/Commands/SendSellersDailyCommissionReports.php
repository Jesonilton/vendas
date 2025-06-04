<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CommissionService;

class SendSellersDailyCommissionReports extends Command
{
    public function __construct(protected CommissionService $commissionService)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-sellers-daily-commission-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily commission report to each seller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->commissionService->sendDailyEmailReportToAllSellers();
    }
}
