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
                            {--limit=100 : Limit the number of products to sync}';

    protected $description = 'Synchronize Stripe plans with local database';

    protected $stripe;

    public function __construct()
    {
        parent::__construct();
        $this->stripe = new StripeClient(config('cashier.secret'));
    }


    public function handle()
    {
        $this->info('Starting Stripe plan synchronization...');

        try {
            // Prepare query parameters
            $queryParams = [
                'active' => true,
                'limit' => $this->option('limit')
            ];

            // Fetch products from Stripe
            $products = $this->stripe->products->all($queryParams);

            // Create a progress bar
            $progressBar = $this->output->createProgressBar(count($products->data));
            $progressBar->start();

            $syncedPlans = 0;
            $skippedPlans = 0;

            foreach ($products->data as $product) {
                // Fetch prices for each product
                $prices = $this->stripe->prices->all([
                    'product' => $product->id,
                    'active' => true
                ]);

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
                                'amount' => $price->unit_amount * 0.01, // Convert cents to dollars
                                'interval' => $price->recurring->interval,
                                'status' => $product->active,
                            ]
                        );

                        $syncedPlans++;
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
            $this->line("Skipped Plans: <comment>$skippedPlans</comment>");

            return Command::SUCCESS;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->error('Stripe API Error: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->error('Synchronization failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
