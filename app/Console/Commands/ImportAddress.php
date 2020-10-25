<?php

namespace App\Console\Commands;

use App\Models\Address;
use Illuminate\Console\Command;
use Storage;

class ImportAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importAddress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Address::truncate();

        $initLocalData = json_decode(Storage::get('address/0/0.json'));
        foreach ($initLocalData as $localData) {
            foreach ($localData->data as $area) {
                echo '.';

                $folder        = substr($area->filename, 0, 1);
                $abbreviations = json_decode(Storage::get("address/$folder/$area->filename.json"));

                foreach ($abbreviations as $abbreviation) {
                    Address::create([
                        'city'         => $localData->city,
                        'zip'          => $area->zip,
                        'area'         => $area->area,
                        'road'         => $abbreviation->name,
                        'abbreviation' => $abbreviation->abc
                    ]);
                }

//                foreach ($abbreviations as $abbreviation) {
//                    dd($abbreviation->name);
//                    if ($abbreviation->name == $area) {
//                        return $abbreviation->abc;
//                    }
//                }
            }

        }

        $this->info('done');
    }

    function getAbbreviation($filename, $area)
    {
        dd($area);
        $folder        = substr($filename, 0, 1);
        $abbreviations = json_decode(Storage::get("address/$folder/$filename.json"));

        foreach ($abbreviations as $abbreviation) {
            dd($abbreviation->name);
            if ($abbreviation->name == $area) {
                return $abbreviation->abc;
            }
        }

        return '';
    }
}
