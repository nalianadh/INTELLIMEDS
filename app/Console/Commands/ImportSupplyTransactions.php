<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SupplyTransaction;
use App\Models\StockRequest;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportSupplyTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:supply-transactions {--dry-run : Run without actually inserting data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from supply_transaction table into stock_requests table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no data will be inserted');
        }

        $this->info('Starting import from supply_transaction to stock_requests...');
        
        // Get all supply transactions
        $supplyTransactions = SupplyTransaction::all();
        $totalTransactions = $supplyTransactions->count();
        
        if ($totalTransactions === 0) {
            $this->warn('No supply transactions found to import.');
            return 0;
        }

        $this->info("Found {$totalTransactions} supply transactions to process.");
        
        $successCount = 0;
        $failedCount = 0;
        $failedItems = [];

        // Create progress bar
        $progressBar = $this->output->createProgressBar($totalTransactions);
        $progressBar->start();

        DB::beginTransaction();

        try {
            foreach ($supplyTransactions as $transaction) {
                // Find the item by matching Stock_ID with i_stockID
                $item = Item::where('i_stockID', $transaction->Stock_ID)->first();
                
                if ($item) {
                    if (!$dryRun) {
                        StockRequest::create([
                            'rq_requested_by' => 2,
                            'user_id' => 2,
                            'item_id' => $item->item_id,
                            'rq_quantity_requested' => $transaction->Quantity,
                            'rq_qty_approved' => $transaction->Quantity,
                            'rq_status' => 'Approved',
                            'rq_date_requested' => $transaction->Date,
                            'rq_date_approved' => $transaction->Date,
                            'rq_approved_by' => 1,
                            'rq_remarks' => "Imported from supply transaction",
                        ]);
                    }
                    $successCount++;
                } else {
                    $failedCount++;
                    $failedItems[] = [
                        'Stock_ID' => $transaction->Stock_ID,
                        'Stock' => $transaction->Stock,
                    ];
                    Log::warning("Item not found for Stock_ID: {$transaction->Stock_ID}");
                }
                
                $progressBar->advance();
            }

            if (!$dryRun) {
                DB::commit();
                $this->newLine(2);
                $this->info("✓ Successfully imported {$successCount} records into stock_requests.");
            } else {
                DB::rollBack();
                $this->newLine(2);
                $this->info("✓ Dry run complete. Would have imported {$successCount} records.");
            }

            if ($failedCount > 0) {
                $this->newLine();
                $this->warn("✗ Failed to process {$failedCount} records (items not found).");
                
                if ($this->confirm('Do you want to see the failed items?', true)) {
                    $this->table(
                        ['Stock_ID', 'Stock Name'],
                        $failedItems
                    );
                }
            }

            $progressBar->finish();
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error('Import failed: ' . $e->getMessage());
            Log::error('Supply transaction import failed: ' . $e->getMessage());
            return 1;
        }
    }
}