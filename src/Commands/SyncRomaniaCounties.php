<?php

namespace Mchervenkov\Inout\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Inout;
use Mchervenkov\Inout\Models\InoutRomaniaCounty;

class SyncRomaniaCounties extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inout:sync-romania-counties
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Inout API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Inout API Romania Counties and saves it into the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('-> Inout Romania Counties');

        try {

            $this->clear();

            $inout = $this->initInoutClient();

            $this->insertCounties($inout);

            $this->info(PHP_EOL . 'Status: ' . self::API_STATUS_OK);

        } catch (\Exception $e) {

            $this->newLine();
            $this->error(PHP_EOL . 'Status: ' . self::API_STATUS_NOT_OK);
            $this->error(
                $e->getMessage()
            );

            return 1;
        }

        return 0;
    }

    /**
     * @param Inout $inout
     * @return void
     * @throws InoutException
     */
    protected function insertCounties(Inout $inout) : void
    {
        $response = $inout->getRomaniaCounties();

        if (! empty($response)) {

            $bar = $this->output->createProgressBar(
                count($response)
            );

            foreach ($response as $county) {
                $county['country_id'] = data_get($county, 'id');

                InoutRomaniaCounty::create($county);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    /**
     * clear
     *
     * @return void
     */
    private function clear()
    {
        if ($days = $this->option('clear')) {
            $clearDate = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

            $this->info("-> Inout Romania Counties: Clearing entries older than: $clearDate");

            InoutRomaniaCounty::query()
                ->where('created_at', '<=', $clearDate)
                ->delete();
        }
    }

    /**
     * @return Inout
     */
    private function initInoutClient(): Inout
    {
        $inout = new Inout();

        if($timeout = $this->option('timeout')) {
            $inout->setTimeout((int)$timeout);
        }

        return $inout;
    }
}
