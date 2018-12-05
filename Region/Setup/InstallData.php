<?php

namespace MagentoJapan\Region\Setup;

use Magento\Directory\Helper\Data;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Regions list and sort order for ja_JP locale.
 */
class InstallData implements InstallDataInterface
{
    /**
     * Directory data
     *
     * @var Data
     */
    private $directoryData;

    /**
     * Constructor
     *
     * @param Data $directoryData Directory data
     */
    public function __construct(Data $directoryData)
    {
        $this->directoryData = $directoryData;
    }

    /**
     * @inheritdoc
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        /**
         * Fill table directory/country_region
         * Fill table directory/country_region_name for en_US locale
         */
        $data = [
            ['JP', '01', 'Hokkaido', '北海道'],
            ['JP', '02', 'Aomori', '青森県'],
            ['JP', '03', 'Iwate', '岩手県'],
            ['JP', '04', 'Miyagi', '宮城県'],
            ['JP', '05', 'Akita', '秋田県'],
            ['JP', '06', 'Yamagata', '山形県'],
            ['JP', '07', 'Fukushima', '福島県'],
            ['JP', '08', 'Ibaraki', '茨城県'],
            ['JP', '09', 'Tochigi', '栃木県'],
            ['JP', '10', 'Gunma', '群馬県'],
            ['JP', '11', 'Saitama', '埼玉県'],
            ['JP', '12', 'Chiba', '千葉県'],
            ['JP', '13', 'Tokyo', '東京都'],
            ['JP', '14', 'Kanagawa', '神奈川県'],
            ['JP', '15', 'Niigata', '新潟県'],
            ['JP', '16', 'Toyama', '富山県'],
            ['JP', '17', 'Ishikawa', '石川県'],
            ['JP', '18', 'Fukui', '福井県'],
            ['JP', '19', 'Yamanashi', '山梨県'],
            ['JP', '20', 'Nagano', '長野県'],
            ['JP', '21', 'Gifu', '岐阜県'],
            ['JP', '22', 'Shizuoka', '静岡県'],
            ['JP', '23', 'Aichi', '愛知県'],
            ['JP', '24', 'Mie', '三重県'],
            ['JP', '25', 'Shiga', '滋賀県'],
            ['JP', '26', 'Kyoto', '京都府'],
            ['JP', '27', 'Osaka', '大阪府'],
            ['JP', '28', 'Hyogo', '兵庫県'],
            ['JP', '29', 'Nara', '奈良県'],
            ['JP', '30', 'Wakayama', '和歌山県'],
            ['JP', '31', 'Tottori', '鳥取県'],
            ['JP', '32', 'Shimane', '島根県'],
            ['JP', '33', 'Okayama', '岡山県'],
            ['JP', '34', 'Hiroshima', '広島県'],
            ['JP', '35', 'Yamaguchi', '山口県'],
            ['JP', '36', 'Tokushima', '徳島県'],
            ['JP', '37', 'Kagawa', '香川県'],
            ['JP', '38', 'Ehime', '愛媛県'],
            ['JP', '39', 'Kochi', '高知県'],
            ['JP', '40', 'Fukuoka', '福岡県'],
            ['JP', '41', 'Saga', '佐賀県'],
            ['JP', '42', 'Nagasaki', '長崎県'],
            ['JP', '43', 'Kumamoto', '熊本県'],
            ['JP', '44', 'Oita', '大分県'],
            ['JP', '45', 'Miyazaki', '宮崎県'],
            ['JP', '46', 'Kagoshima', '鹿児島県'],
            ['JP', '47', 'Okinawa', '沖縄県']
        ];

        $connection = $setup->getConnection();
        $regionTable = $setup->getTable('directory_country_region');
        $regionNameTable = $setup->getTable('directory_country_region_name');

        foreach ($data as $row) {
            $bind = [
                'country_id' => $row[0],
                'code' => $row[2],
                'default_name' => $row[2]
            ];
            $connection->insertOnDuplicate($regionTable, $bind);
            $regionId = $connection->lastInsertId($regionTable);

            $bind = [
                'locale' => 'en_US',
                'region_id' => $regionId,
                'name' => $row[2]
            ];
            $connection->insertOnDuplicate($regionNameTable, $bind);

            $bind = [
                'locale' => 'ja_JP',
                'region_id' => $regionId,
                'name' => $row[3]
            ];
            $connection->insertOnDuplicate($regionNameTable, $bind);
        }
    }
}
