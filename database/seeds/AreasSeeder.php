<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->truncate();

        $xml = simplexml_load_file(base_path('import/list.xml'));

        $provinceId = 0;
        $cityId = 0;

        foreach ($xml->dict->children() as $child) {
            $name = $child->getName();

            // 省或者直辖市，type=1
            if ($name == 'key') {
                list($id, $val) = $this->split((string)$child);

                DB::table('areas')->insertGetId([
                    'area_id' => $id,
                    'name' => $val,
                    'type' => 1,
                    'pid'  => 0
                ]);

                $provinceId = $id;

            } else if ($name == 'array') {
                // 市所属的区
                foreach ($child->children() as $secondChild) {
                    list($id, $val) = $this->split((string)$secondChild);

                    DB::table('areas')->insert([
                        'area_id' => $id,
                        'name' => $val,
                        'type' => 2,
                        'pid'  => $provinceId
                    ]);
                }
            } else if ($name == 'dict') {
                foreach ($child->children() as $secondChild) {
                    // 市区
                    if ($secondChild->getName() == 'key') {

                        list($id, $val) = $this->split((string)$secondChild);

                        DB::table('areas')->insertGetId([
                            'area_id' => $id,
                            'name' => $val,
                            'type' => 2,
                            'pid'  => $provinceId
                        ]);

                        $cityId = $id;
                    }
                    // 区
                    else if ($secondChild->getName() == 'array') {
                        foreach ($secondChild->children() as $thirdChild) {
                            list($id, $val) = $this->split((string)$thirdChild);

                            DB::table('areas')->insert([
                                'area_id' => $id,
                                'name' => $val,
                                'type' => 3,
                                'pid'  => $cityId
                            ]);
                        }
                    }
                }
            }
        }
    }

    public function split($str)
    {
        $str = $this->str_split_unicode($str);

        $number = '';
        $string = '';

        foreach ($str as $c) {
            if (is_numeric($c)) {
                $number .= $c;
            } else {
                $string .= $c;
            }
        }

        return [$number, $string];
    }

    public function str_split_unicode($str, $l = 0) {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }
}
