<?php

namespace Mchervenkov\Inout\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Inout;
use Mchervenkov\Inout\Models\InoutCompanyCourier;

class SyncCompanyCouriers extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inout:sync-couriers
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Inout API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Inout API Couriers and saves it into the database';

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
        $this->info('-> Inout Couriers');

        try {

            $this->clear();

            $inout = $this->initInoutClient();

            $this->insertCouriers($inout);

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
    protected function insertCouriers(Inout $inout) : void
    {
        $response = $inout->getCouriers();

        if (! empty($response)) {
            foreach ($response as $courier) {
                InoutCompanyCourier::create($this->getCourierData($courier));
            }
        } else {
            $this->info('There is no couriers for company id ' . $inout->getCompanyId());
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

            $this->info("-> Inout Couriers : Clearing entries older than: $clearDate");

            InoutCompanyCourier::query()
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
    protected function getCourierData(array $data): array
    {
        $name = data_get($data, 'NAME');

        return [
            'courier_id' => data_get($data, 'ID'),
            'signature' => $this->getSignature($name),
            'name' => $name,
            'to_office' => data_get($data, 'TO_OFFICE'),
            'to_address' => data_get($data, 'TO_ADDRESS'),
            'country' => data_get($data, 'COUNTRY'),
            'currency' => data_get($data, 'CURRENCY'),
        ];
    }

    /**
     * @param string $courierName
     * @return string
     */
    protected function getSignature(string $courierName): string
    {
        return "INOUT_" . Str::upper(Str::snake($courierName));
    }
}
