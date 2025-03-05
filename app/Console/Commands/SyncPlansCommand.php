<?php

namespace App\Console\Commands;

use App\Models\Plan;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class SyncPlansCommand extends Command
{
    protected $signature = 'plans:sync
                            {--force : Force sync even if there are potential issues}
                            {--limit=100 : Limit the number of products to sync}
                            {--all : Sync both active and inactive plans}';

    protected $description = 'Synchronize Stripe plans with local database';

    protected $stripe;

    public function __construct()
    {
        parent::__construct();
        $this->stripe = new StripeClient(config('cashier.secret'));
    }

    public function handle(): int
    {
        $this->info('Starting Stripe plan synchronization...');

        try {
            // Prepare query parameters
            $queryParams = [
                'limit' => $this->option('limit')
            ];

            // Add active filter only if --all is not specified
            if (!$this->option('all')) {
                $queryParams['active'] = true;
            }

            // Fetch products from Stripe
            $products = $this->stripe->products->all($queryParams);

            // Create a progress bar
            $progressBar = $this->output->createProgressBar(count($products->data));
            $progressBar->start();

            $syncedPlans = 0;
            $skippedPlans = 0;
            $inactivePlans = 0;

            foreach ($products->data as $product) {
                // Fetch prices for each product
                $priceParams = [
                    'product' => $product->id
                ];

                // Add active filter only if --all is not specified
                if (!$this->option('all')) {
                    $priceParams['active'] = true;
                }

                $prices = $this->stripe->prices->all($priceParams);

                foreach ($prices->data as $price) {
                    // Only process recurring prices
                    if ($price->type === 'recurring') {
                        $plan = Plan::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'price_id' => $price->id
                            ],
                            [
                                'name' => $product->name,
                                'slug' => Str::slug($product->name),
                                'description' => $product->description ?? null,
                                'amount' => $price->unit_amount / 100, // Convert cents to dollars
                                'interval' => $price->recurring->interval,
                                'status' => $product->active,
                            ]
                        );

                        $syncedPlans++;

                        if (!$product->active) {
                            $inactivePlans++;
                        }
                    } else {
                        $skippedPlans++;
                    }
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();

            // Display summary
            $this->info("Synchronization complete!");
            $this->line("Synced Plans: <info>$syncedPlans</info>");

            if ($this->option('all')) {
                $this->line("Synced Inactive Plans: <comment>$inactivePlans</comment>");
            }

            $this->line("Skipped Plans: <comment>$skippedPlans</comment>");

            return self::SUCCESS;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->error('Stripe API Error: '.$e->getMessage());
            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error('Synchronization failed: '.$e->getMessage());
            return self::FAILURE;
        }
    }
}
