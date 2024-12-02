<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Http;
use App\Models\ForexRate;
class UpdateForexRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-forex-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiUrl = 'https://api.exchangerate-api.com/v4/latest/USD'; // Replace with your API URL
        $response = Http::get($apiUrl);

        if ($response->successful()) {
            $data = $response->json();

            $baseCurrency = $data['base'];
            foreach ($data['rates'] as $currency => $rate) {
                ForexRate::updateOrCreate(
                    ['base_currency' => $baseCurrency, 'target_currency' => $currency],
                    ['rate' => $rate]
                );
            }

            $this->info('Forex rates updated successfully.');
        } else {
            $this->error('Failed to fetch Forex rates.');
        }
    
    }
}
