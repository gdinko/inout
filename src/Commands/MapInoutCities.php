<?php

namespace Mchervenkov\Inout\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Mchervenkov\Inout\Exceptions\InoutImportValidationException;
use Mchervenkov\Inout\Inout;
use Mchervenkov\Inout\Models\CarrierCityMap;
use Mchervenkov\Inout\Models\InoutCompanyCourier;
use Mchervenkov\Inout\Models\InoutCountry;
use Mchervenkov\Inout\Models\InoutCourierOffice;
use Ramsey\Uuid\Uuid;

class MapInoutCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inout:map-cities
                            {courier_id : Inout Courier Id}
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

            if (! $this->import($inout)) {
                return 1;
            }

            $this->newLine(2);

        } catch (\Throwable $e) {
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
        if (! Schema::hasTable('inout_cities')) {
            $this->error('Inout Cities table does not exist. Please run Inout migrations before continue!');

            return false;
        }

        if (! Schema::hasTable('inout_courier_offices')) {
            $this->error('Inout Courier Offices table does not exist. Please run Inout migrations before continue!');

            return false;
        }

        if (! Schema::hasTable('inout_countries')) {
            $this->error('Inout Countries table does not exist. Please run Inout migrations before continue!');

            return false;
        }

        $countryCode = strtoupper($this->argument('country_code'));

        /** @var InoutCompanyCourier|null $courier */
        $courier = $this->getCourier();

        if (! $courier) {
            $this->error('Inout Company Courier cannot be found!');

            return false;
        }

        $hasMissingCities = false;

        foreach ($courier->offices as $office) {
            if ($office->city === null && $office->groupCity === null) {
                $this->error("Missing city: office_id={$office->office_id}, city_id={$office->city_id}. Sync cities before mapping.");
                $hasMissingCities = true;
            }
        }

        if ($hasMissingCities) {
            return false;
        }

        return DB::transaction(function () use ($inout, $countryCode, $courier): bool {

            if ($courier) {

                /** @var InoutCountry $country */
                $country = $this->getCountry($countryCode);

                if ($courier->offices->isNotEmpty()) {

                    $this->info("Map Cities for Courier: {$courier->name}");

                    $bar = $this->output->createProgressBar(
                        count($courier->offices)
                    );

                    $bar->start();

                    try {

                        /** @var InoutCourierOffice $office */
                        foreach ($courier->offices as $office) {

                            $city = $office->city ?? $office->groupCity;
                            $postCode = $office->post_code ?? $city->postal_code ?? '';

                            $name = $this->normalizeCityName(
                                $city->name_en ?: ($city->name_local ?: $office->city_name)
                            );

                            $nameSlug = $this->getSlug($name);

                            $slug = $this->getSlug(
                                $country->iso_code . ' ' . $nameSlug . ' ' . $postCode
                            );

                            $data = [
                                'carrier_signature' => $inout->getSignature(),
                                'carrier_city_id' => $city->city_id,
                                'country_code' => $country->iso_code,
                                'region' => Str::title($office->region ?? ''),
                                'name' => $name,
                                'name_slug' => $nameSlug,
                                'post_code' => $postCode,
                                'slug' => $slug,
                                'uuid' => $this->getUuid($slug),
                            ];

                            CarrierCityMap::query()->updateOrCreate(
                                [
                                    'carrier_signature' => $data['carrier_signature'],
                                    'country_code' => $data['country_code'],
                                    'carrier_city_id' => $data['carrier_city_id'],
                                    'post_code' => $postCode,
                                ],
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

                        throw $eive;
                    }

                    $bar->finish();
                }
            }

            return true;
        });
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
    protected function initInoutClient(): Inout
    {
        $inout = new Inout();

        if ($timeout = $this->option('timeout')) {
            $inout->setTimeout((int)$timeout);
        }

        return $inout;
    }

    /**
     * @param string $countryCode
     * @return Model
     */
    protected function getCountry(string $countryCode): Model
    {
        return InoutCountry::query()
            ->where('iso_code', $countryCode)
            ->firstOrFail();
    }

    /**
     * @return Model|null
     */
    protected function getCourier(): Model|null
    {
        $countryCode = $this->argument('courier_id');

        return InoutCompanyCourier::query()
            ->where('courier_id', $countryCode)
            ->with([
                'offices.city',
                'offices.groupCity',
            ])
            ->first();
    }
}
