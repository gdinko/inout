<?php

namespace Mchervenkov\Inout\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Hydrators\City;
use Mchervenkov\Inout\Inout;
use Mchervenkov\Inout\Models\InoutCity;

class SyncCities extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inout:sync-cities
                            {country_id : Inout Country Id}
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Inout API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Inout API Cities and saves it into the database';

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
        $countryId = $this->argument('country_id');

        $this->info('-> Inout Cities');

        try {

            $this->clear();

            $inout = $this->initInoutClient();
            $city = $this->initCityHydrator();

            $this->insertCities($inout, $city, $countryId);

            $this->info(PHP_EOL . 'Status: ' . self::API_STATUS_OK);

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
     * @param City $city
     * @param int $countryId
     * @return void
     * @throws InoutException
     */
    protected function insertCities(Inout $inout, City $city, int $countryId) : void
    {
        $response = $inout->getCities($city, $countryId);

        if (! empty($response)) {

            $bar = $this->output->createProgressBar(
                count($response)
            );

            foreach ($response as $city) {
                InoutCity::create($this->getCityData($city));
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

            $this->info("-> Inout Cities : Clearing entries older than: $clearDate");

            InoutCity::query()
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

    /**
     * @return City
     */
    private function initCityHydrator(): City
    {
        return new City([
            'paging' => 0
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function getCityData(array $data): array
    {
        return [
            'city_id' => data_get($data, 'ID'),
            'county_id' => data_get($data, 'COUNTY_ID'),
            'postal_code' => data_get($data, 'POSTAL_CODE'),
            'name_local' => data_get($data, 'CITY_NAME_LOCAL'),
            'name_en' => data_get($data, 'CITY_NAME_EN'),
            'county_name' => data_get($data, 'COUNTY'),
            'county_name_en' => data_get($data, 'COUNTY_EN'),
            'municipality' => data_get($data, 'MUNICIPALITY'),
            'state_name' => data_get($data, 'STATE'),
            'state_name_en' => data_get($data, 'STATE_EN'),
            'manual_edit' => data_get($data, 'MANUAL_EDIT'),
        ];
    }
}
