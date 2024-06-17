<?php

namespace Mchervenkov\Inout\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Inout;
use Mchervenkov\Inout\Models\InoutCompanyCourier;
use Mchervenkov\Inout\Models\InoutCourierOffice;

class SyncCourierOffices extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inout:sync-courier-offices
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Inout API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Inout API Courier Offices and saves it into the database';

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

        if(!Schema::hasTable('inout_company_couriers')) {
            $this->error('Inout Company Couriers table does not exist. Please run inout migration before continue!');
            return 1;
        }

        $companyCouriers = $this->getCompanyCouriers();

        if($companyCouriers->isEmpty()) {
            $this->error('Inout Company Couriers table is empty. Please sync company couriers before continue!');
            return 1;
        }

        $this->info('-> Inout Courier Offices');

        try {

            $this->clear();

            $inout = $this->initInoutClient();

            $this->insertOffices($inout, $companyCouriers);

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
     * @param Collection $companyCouriers
     * @return void
     * @throws InoutException
     */
    protected function insertOffices(Inout $inout, Collection $companyCouriers) : void
    {
        /** @var InoutCompanyCourier $companyCourier */
        foreach ($companyCouriers as $companyCourier) {
            $this->info("Sync $companyCourier->name Offices");

            $response = $inout->getCourierOffices($companyCourier->courier_id);

            if (! empty($response)) {

                $bar = $this->output->createProgressBar(
                    count($response)
                );

                foreach ($response as $office) {
                    InoutCourierOffice::create($this->getOfficeData($office));
                    $bar->advance();
                }

                $bar->finish();
            }
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

            $this->info("-> Inout Company Courier Offices: Clearing entries older than: $clearDate");

            InoutCompanyCourier::query()
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
     * @param array $data
     * @return array
     */
    private function getOfficeData(array $data): array
    {
        return [
            'office_id' => data_get($data, 'ID'),
            'city_id' => data_get($data, 'CITY_ID'),
            'courier_id' => data_get($data, 'COURIER_ID'),
            'courier_office_id' => data_get($data, 'COURIER_OFFICE_ID'),
            'group_city_id' => data_get($data, 'GROUP_CITY_ID'),
            'office_name' => data_get($data, 'OFFICE_NAME'),
            'courier_office_code' => data_get($data, 'COURIER_OFFICE_CODE'),
            'city_name' => data_get($data, 'CITY_NAME'),
            'address' => data_get($data, 'ADDRESS'),
            'latitude' => data_get($data, 'LATITUDE'),
            'longitude' => data_get($data, 'LONGITUDE'),
            'work_end' => data_get($data, 'WORK_END'),
            'work_begin' => data_get($data, 'WORK_BEGIN'),
            'work_begin_saturday' => data_get($data, 'WORK_BEGIN_SATURDAY'),
            'work_end_saturday' => data_get($data, 'WORK_END_SATURDAY'),
            'post_code' => data_get($data, 'POST_CODE'),
            'region' => data_get($data, 'REGION'),
        ];
    }

    /**
     * @return Collection
     */
    private function getCompanyCouriers(): Collection
    {
        return InoutCompanyCourier::all();
    }
}
