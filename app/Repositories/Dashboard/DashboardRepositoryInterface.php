<?php

namespace App\Repositories\Dashboard;

interface DashboardRepositoryInterface
{
    public function dashboard($request);

    public function monthlySales($request);

    public function monthlyUsers($request);
}
