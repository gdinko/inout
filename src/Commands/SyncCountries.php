<?php

namespace Mchervenkov\Inout\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Inout;
use Mchervenkov\Inout\Models\InoutCountry;

class SyncCountries extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inout:sync-countries
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Inout API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Inout API Countries and saves it into the database';

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
        $this->info('-> Inout Countries');

        try {

            $this->clear();

            $inout = $this->initInoutClient();

            $this->insertCountries($inout);

            $this->info('Status: ' . self::API_STATUS_OK);

        } catch (\Exception $e) {

            $this->newLine();
            $this->error('Status: ' . self::API_STATUS_NOT_OK);
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
    protected function insertCountries(Inout $inout) : void
    {
        $response = $inout->getCountries();

        if (! empty($response)) {
            foreach ($response as $country) {
                InoutCountry::create($this->getCountryData($country));
            }
        }
    }

    /**
     * clear
     *
     * @return void
     */
    protected function clear()
    {
        if ($days = $this->option('clear')) {
            $clearDate = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

            $this->info("-> Inout Countries : Clearing entries older than: $clearDate");

            InoutCountry::query()
                ->where('created_at', '<=', $clearDate)
                ->delete();
        }
    }

    /**
     * @return Inout
     */
    protected function initInoutClient(): Inout
    {
        $inout = new Inout();

        if($timeout = $this->option('timeout')) {
            $inout->setTimeout((int)$timeout);
        }

        return $inout;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getCountryData(array $data): array
    {
        return [
            'country_id' => data_get($data, 'ID'),
            'name' => data_get($data, 'NAME'),
            'cyrillic_name' => data_get($data, 'CYRILLIC_NAME'),
            'iso_code' => data_get($data, 'ISO_CODE'),
        ];
    }
}
