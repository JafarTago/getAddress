<?php

namespace App\Console\Commands;

use App\Models\Address;
use Illuminate\Console\Command;

class tmp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmp';

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

    protected $address = '台北市大同區重慶北路一段30號B1(走到底左轉)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->address = $this->convertStrType($this->address);

        $city         = $this->getCity();
        $zip          = $this->getZip($city);
        $area         = $this->getArea();
        $road         = $this->getRoad() . $this->getSection();
        $lane         = $this->getLane();
        $alley        = $this->getAlley();
        $no           = $this->getNo();
        $floor        = $this->getFloor() . $this->getBasement();
        $address      = $this->address;
        $filename     = $zip . '.json';
        $latitude     = null;
        $lontitue     = null;
        $full_address = $zip . $city . $area . $road . $lane . $alley . $no . $floor;



        $GuzzleClient = new \GuzzleHttp\Client();
        $addressUTF8 = str_replace('"]', '', str_replace('["', '', json_encode([$full_address])));
        $response     = $GuzzleClient->request('GET',
            'https://maps.googleapis.com/maps/api/geocode/json?address=' . $addressUTF8 . '&key=AIzaSyDoNPWp-LVfOKpfclro_qR0FhGUDTJEDig');

        dd($response->getBody()->getContents());

    }


    private function getZip($city)
    {
        $address = Address::where('city', 'like', '%' . $city . '%')->first();

        if (is_null($address)) {
            return null;
        }

        return $address->zip;
    }

    private function getCity()
    {
        $city = explode('市', $this->address);

        if (count($city) > 1) {
            $city = $city[0] . '市';
        } else {
            $city = explode('縣', $this->address);
            if (count($city) > 1) {
                $city = $city[0] . '縣';
            }
        }

        if (is_string($city)) {
            $this->address = explode($city, $this->address)[1];

            return $city;
        }

        return null;
    }

    private function getArea()
    {
        $area = explode('區', $this->address);

        if (count($area) > 1) {
            $area = $area[0] . '區';
        } else {
            $area = explode('鄉', $this->address);
            if (count($area) > 1) {
                $area = $area[0] . '鄉';
            } else {
                $area = explode('鎮', $this->address);
                if (count($area) > 1) {
                    $area = $area[0] . '鎮';
                }
            }
        }

        if (is_string($area)) {
            $this->address = explode($area, $this->address)[1];

            return $area;
        }

        return null;
    }

    private function getRoad()
    {
        $road = explode('路', $this->address);

        if (count($road) > 1) {
            $road = $road[0] . '路';
        } else {
            $road = explode('街', $this->address);
            if (count($road) > 1) {
                $road = $road[0] . '街';
            }
        }

        if (is_string($road)) {
            $this->address = explode($road, $this->address)[1];

            return $road;
        }

        return null;
    }


    private function getSection()
    {
        $section = explode('段', $this->address);

        if (count($section) > 1) {
            $section = $section[0] . '段';
        }

        if (is_string($section)) {
            $this->address  = explode($section, $this->address)[1];
            $sectionExplore = explode('段', $section);

            if ((int)$sectionExplore[0]) {
                return $this->conversionSection($sectionExplore[0]) . '段';
            }

            return $section;
        }

        return null;
    }

    private function conversionSection($sectionNumber)
    {
        for ($i = 1; $i <= 10; $i++) {
            switch ($i) {
                case 1:
                    return '一';
                    break;
                case 2:
                    return '二';
                    break;
                case 3:
                    return '三';
                    break;
                case 4:
                    return '四';
                    break;
                case 5:
                    return '五';
                    break;
                case 6:
                    return '六';
                    break;
                case 7:
                    return '七';
                    break;
                case 8:
                    return '八';
                    break;
                case 9:
                    return '九';
                    break;
                case 10:
                    return '十';
                    break;
            }
        }

    }

    private function getLane()
    {
        $lane = explode('巷', $this->address);

        if (count($lane) > 1) {
            $lane = $lane[0] . '巷';
        }

        if (is_string($lane)) {
            $this->address = explode($lane, $this->address)[1];

            return $lane;
        }

        return null;
    }

    private function getAlley()
    {
        $alley = explode('弄', $this->address);

        if (count($alley) > 1) {
            $alley = $alley[0] . '弄';
        }

        if (is_string($alley)) {
            $this->address = explode($alley, $this->address)[1];

            return $alley;
        }

        return null;
    }

    private function getNo()
    {
        $no = explode('號', $this->address);

        if (count($no) > 1) {
            $no = $no[0] . '號';
        }

        if (is_string($no)) {
            $this->address = explode($no, $this->address)[1];

            return $no;
        }

        return null;
    }

    private function getFloor()
    {
        $floor = explode('樓', $this->address);

        if (count($floor) > 1) {
            $floor = $floor[0] . '樓';
        }

        if (is_string($floor)) {
            $this->address = explode($floor, $this->address)[1];

            return $floor;
        }

        return null;
    }

    private function getBasement()
    {
        if (substr($this->address, 0, 1) == 'B') {
            $basement = substr($this->address, 0, 2);
        }

        if (is_string($basement)) {
            $this->address = explode($basement, $this->address)[1];

            return $basement;
        }

        return null;
    }

    function convertStrType($number)
    {
        $dbc = [
            '０', '１', '２', '３', '４', '５', '６', '７', '８', '９',
            '号', '台', '之',
            'F', 'f', 'ｆ', 'Ｆ',
            'b', 'ｂ', 'Ｂ'
        ];

        $sbc = [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            '號', '臺', '-',
            '樓', '樓', '樓', '樓',
            'B', 'B', 'B',
        ];

        return str_replace($dbc, $sbc, $number);
    }

    function unicode_to_utf8($unicode_str)
    {
        $utf8_str = '';
        $code     = intval(hexdec($unicode_str));
//這裡注意轉換出來的code一定得是整形，這樣才會正確的按位操作
        $ord_1    = decbin(0xe0 | ($code >> 12));
        $ord_2    = decbin(0x80 | (($code >> 6) & 0x3f));
        $ord_3    = decbin(0x80 | ($code & 0x3f));
        $utf8_str = chr(bindec($ord_1)) . chr(bindec($ord_2)) . chr(bindec($ord_3));
        return $utf8_str;
    }
}
