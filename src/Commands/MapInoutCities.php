<?php

namespace Mchervenkov\Inout\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mchervenkov\Inout\Exceptions\InoutImportValidationException;
use Mchervenkov\Inout\Inout;
use Mchervenkov\Inout\Models\CarrierCityMap;
use Mchervenkov\Inout\Models\InoutCity;
use Mchervenkov\Inout\Models\InoutCompanyCourier;
use Mchervenkov\Inout\Models\InoutCountry;
use Mchervenkov\Inout\Models\InoutCourierOffice;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Schema;

class MapInoutCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inout:map-cities
                            {country_code : Country ALPHA 2 ISO 3166 code}
                            {--timeout=20 : Inout API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Inout cities and makes carriers city map in database';

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
        $this->info('-> Inout Map Cities');

        try {

            $inout = $this->initInoutClient();

            $this->import($inout);

            $this->newLine(2);

        } catch (\Exception $e) {
            $this->newLine();
            $this->error(
                $e->getMessage()
            );

            return 1;
        }

        return 0;
    }

    /**
     * import
     *
     */
    protected function import(Inout $inout): bool
    {
        if(!Schema::hasTable('inout_cities')) {
            $this->error('Inout Cities table does not exist. Please run Inout migrations before continue!');
            return false;
        }

        if(!Schema::hasTable('inout_courier_offices')) {
            $this->error('Inout Courier Offices table does not exist. Please run Inout migrations before continue!');
            return false;
        }

        if(!Schema::hasTable('inout_countries')) {
            $this->error('Inout Countries table does not exist. Please run Inout migrations before continue!');
            return false;
        }

        $countryCode = $this->argument('country_code');

        /** @var InoutCountry $country */
        $country = $this->getCountry($countryCode);
        $couriers = $this->getCouriersByCountry($country->name);

        if ($couriers->isNotEmpty()) {
            CarrierCityMap::where(
                'carrier_signature',
                $inout->getSignature()
            )
                ->where('country_code', strtoupper($countryCode))
                ->delete();

            /** @var InoutCompanyCourier $courier */
            foreach ($couriers as $courier) {

                if($courier->offices->isNotEmpty()) {

                    $this->info("Map Cities for Courier: {$courier->name}");

                    $bar = $this->output->createProgressBar(
                        count($courier->offices)
                    );

                    $bar->start();

                    try {

                        /** @var InoutCourierOffice $office */
                        foreach ($courier->offices as $office) {

                            $name = $this->normalizeCityName(
                                $office->city->name_local
                            );

                            $nameSlug = $this->getSlug($name);

                            $slug = $this->getSlug(
                                $nameSlug . ' ' . $office->city->postal_code
                            );

                            $data = [
                                'carrier_signature' => $inout->getSignature(),
                                'carrier_city_id' => $office->city->city_id,
                                'country_code' => $country->iso_code,
                                'region' => Str::title($office->region),
                                'name' => $name,
                                'name_slug' => $nameSlug,
                                'post_code' => $office->city->postal_code,
                                'slug' => $slug,
                                'uuid' => $this->getUuid($slug),
                            ];

                            CarrierCityMap::create(
                                $data
                            );

                            $office->city_uuid = $data['uuid'];
                            $office->save();

                            $bar->advance();
                        }

                    } catch (InoutImportValidationException $eive) {
                        $this->newLine();
                        $this->error(
                            $eive->getMessage()
                        );
                        $this->info(
                            print_r($eive->getData(), true)
                        );
                        $this->error(
                            print_r($eive->getErrors(), true)
                        );
                    }

                    $bar->finish();
                }
            }
        }

        return true;
    }

    /**
     * normalizeCityName
     *
     * @param  string $name
     * @return string
     */
    protected function normalizeCityName(string $name): string
    {
        return Str::title(
            explode(',', $name)[0]
        );
    }

    /**
     * getSlug
     *
     * @param  string $string
     * @return string
     */
    protected function getSlug(string $string): string
    {
        return Str::slug($string);
    }

    /**
     * getUuid
     *
     * @param  string $string
     * @return string
     */
    protected function getUuid(string $string): string
    {
        return Uuid::uuid5(
            Uuid::NAMESPACE_URL,
            $string
        )->toString();
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
     * @param string $countryCode
     * @return Model
     */
    private function getCountry(string $countryCode): Model
    {
        return InoutCountry::query()
            ->where('is_code', $countryCode)
            ->firstOrFail();
    }

    /**
     * @param string $countryName
     * @return Collection
     */
    private function getCouriersByCountry(string $countryName): Collection
    {
        return InoutCompanyCourier::query()
            ->where('country', $countryName)
            ->with([
                'offices.city'
            ])
            ->get();
    }
}
