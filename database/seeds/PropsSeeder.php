<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PropsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('props')->truncate();

        $reader = IOFactory::createReader('Xlsx');

        $files = ['BoyClothesSetting.xlsx', 'GirlClothesSetting.xlsx', 'GirlHairSetting.xlsx', 'BoyHairSetting.xlsx',];

        foreach ($files as $file) {
            if (strpos($file, 'Hair') !== false) {
                $type = 1;
            } else {
                $type = 2;
            }

            $file = app_path('../import') . '/' . $file;
            $list = $this->buildList($reader, $file, $type);
            DB::table('props')->insert($list);
        }
    }


    private function buildList($reader, $file, $type)
    {
        $spreadsheet  = $reader->load($file);

        $worksheet = $spreadsheet ->getActiveSheet();

//        // 总行数
        $rows = $worksheet->getHighestRow();

//        // 总列数
//        $columns = $worksheet->getHighestColumn();

        // 需要的字段：prop_id, value, name, desc, src
        // 从第1列开始遍历，找出需要字段所对应的列，然后再遍历行数。
        $require = [
            'prop_id' => 'prop_id',
            'value'   => 'price',
            'name'    => 'prop_name',
            'desc'    => 'remarks',
            'src'     => 'image'
        ];
        $columns = [];

        $list = [];

        for ($i = 1; $i < 20; $i++) {
            $val = $worksheet->getCellByColumnAndRow($i, 1)->getValue();
//            if (in_array($val, $require)) {
//                $columns[$val] = $i;
//            }

            if (isset($require[$val])) {
                $columns[$require[$val]] = $i;
            }
        }

        // 从第5行开始读取数据
        for ($i = 5; $i <= $rows; $i++) {
            $data = [];

            foreach ($columns as $key => $col) {
                $val = $worksheet->getCellByColumnAndRow($col, $i)->getValue();

                if ($val != null) {
                    $data[$key] = $val;
                    $data['type'] = $type;
                    $data['tag'] = 1;
                }
            }

            if ($data != null) {
                $list[] = $data;
            }
        }

        return $list;
    }
}
