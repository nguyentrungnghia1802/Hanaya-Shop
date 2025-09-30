<?php

require_once 'vendor/autoload.php';

use Carbon\Carbon;

echo "Debug Revenue Calculation\n";
echo "Current time: " . Carbon::now() . "\n";
echo "Current month: " . Carbon::now()->month . "\n";
echo "Current year: " . Carbon::now()->year . "\n";
echo "Previous month date: " . Carbon::now()->subMonth() . "\n";
echo "Previous month: " . Carbon::now()->subMonth()->month . "\n";
echo "Previous year: " . Carbon::now()->subMonth()->year . "\n";
