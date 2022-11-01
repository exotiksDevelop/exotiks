<?php
class Pochtaros {
    private $methods = array(
        array('name' => 'ПростоеПисьмо', 'key' => 'pismo_easy', 'price' => 'Тариф', 'max_weight' => 100, 'p' => 'ls'),
        array('name' => 'ЗаказноеПисьмо', 'key' => 'pismo_zakaz', 'price' => 'Тариф', 'max_weight' => 100, 'p' => 'lr'),
        array('name' => 'ЦенноеПисьмо', 'key' => 'pismo_price', 'price' => 'Доставка', 'max_weight' => 100, 'p' => 'lv'),
        array('name' => 'ЦенноеПисьмо', 'key' => 'pismo_price_obyavlennaya_stennost', 'price' => 'Доставка', 'max_weight' => 100, 'p' => 'lv'),

       /* array('name' => 'ПростоеПисьмо1Класс', 'key' => 'pismo_easy1', 'price' => 'Тариф', 'max_weight' => 500),*/

        array('name' => 'ЗаказноеПисьмо1Класс', 'key' => 'pismo_zakaz1', 'price' => 'Тариф', 'max_weight' => 500, 'p' => 'l1r'),
        array('name' => 'ЦенноеПисьмо1Класс', 'key' => 'pismo_price1', 'price' => 'Доставка', 'max_weight' => 500, 'p' => 'l1v'),
        array('name' => 'ЦенноеПисьмо1Класс', 'key' => 'pismo_price1_obyavlennaya_stennost', 'price' => 'Доставка', 'max_weight' => 500, 'p' => 'l1v'),

/*        array('name' => 'ПростойМультиконверт', 'key' => 'prostoj_multikonvert', 'price' => 'Тариф', 'max_weight' => 150),
        array('name' => 'ЗаказнойМультиконверт', 'key' => 'zakaznoj_multikonvert', 'price' => 'Тариф', 'max_weight' => 150),
*/

        array('name' => 'ПростаяБандероль', 'key' => 'prostaya_banderol', 'price' => 'Тариф', 'max_weight' => 2000, 'p' => 'bs'),
        array('name' => 'ЗаказнаяБандероль', 'key' => 'zakaznaya_banderol', 'price' => 'Тариф', 'max_weight' => 2000, 'p' => 'br'),
        array('name' => 'ЗаказнаяБандероль1Класс', 'key' => 'zakaznaya_banderol_1_class', 'price' => 'Тариф', 'max_weight' => 2500, 'p' => 'b1r'),
        array('name' => 'ЦеннаяБандероль', 'key' => 'tsennaya_banderol', 'price' => 'Тариф', 'max_weight' => 2000, 'p' => 'bv'),
        array('name' => 'ЦеннаяБандероль', 'key' => 'tsennaya_banderol_obyavlennaya_stennost', 'price' => 'Доставка', 'max_weight' => 2000, 'p' => 'bv'),
        array('name' => 'ЦеннаяПосылка', 'key' => 'tsennaya_posylka', 'price' => 'Тариф', 'max_weight' => 20000, 'p' => 'pv'),
        array('name' => 'ЦеннаяПосылка', 'key' => 'tsennaya_posylka_obyavlennaya_stennost', 'price' => 'Доставка', 'max_weight' => 20000, 'p' => 'pv'),
        array('name' => 'ЦеннаяБандероль1Класс', 'key' => 'tsennaya_banderol_1_class', 'price' => 'Тариф', 'max_weight' => 2500, 'p' => 'b1v'),
        array('name' => 'ЦеннаяБандероль1Класс', 'key' => 'tsennaya_banderol_1_class_obyavlennaya_stennost', 'price' => 'Доставка', 'max_weight' => 2500, 'p' => 'b1v'),
        array('name' => 'Посылка1Класс', 'key' => 'posylka_1_class', 'price' => 'Доставка', 'max_weight' => 2500, 'p' => 'p1'),
        array('name' => 'Посылка1Класс', 'key' => 'posylka_1_class_obyavlennaya_stennost', 'price' => 'Доставка', 'max_weight' => 2500, 'p' => 'p1'),

/*        array('name' => 'ЦеннаяАвиаБандероль', 'key' => 'tsennaya_aviabanderol', 'price' => 'Тариф', 'max_weight' => 2000),
        array('name' => 'ЦеннаяАвиаБандероль', 'key' => 'tsennaya_aviabanderol_obyavlennaya_stennost', 'price' => 'Доставка', 'max_weight' => 2000),
        array('name' => 'ЦеннаяАвиаПосылка', 'key' => 'tsennaya_aviaposylka', 'price' => 'Тариф', 'max_weight' => 2500),
        array('name' => 'ЦеннаяАвиаПосылка', 'key' => 'tsennaya_aviaposylka_obyavlennaya_stennost', 'price' => 'Доставка', 'max_weight' => 2500),*/
        
        array('name' => 'EMS', 'key' => 'ems', 'price' => 'Доставка', 'max_weight' => 31500, 'p' => 'em'),
        array('name' => 'ПосылкаОнлайн', 'key' => 'posylka_online', 'price' => 'Доставка', 'max_weight' => 20000, 'p' => 'po'),
        array('name' => 'КурьерОнлайн', 'key' => 'courier_online', 'price' => 'Доставка', 'max_weight' => 31500, 'p' => 'co'),
        array('name' => 'ЕКОМ', 'key' => 'ekom', 'price' => 'Доставка', 'max_weight' => 15000, 'p' => 'ek'),
        array('name' => 'ЕКОМПартнер', 'key' => 'ekomp', 'price' => 'Доставка', 'max_weight' => 15000, 'p' => 'ekp'),
        array('name' => 'EMSОптимальное', 'key' => 'ems_optimal', 'price' => 'Доставка', 'max_weight' => 20000, 'p' => 'emo'),
        array('name' => 'EMSОптимальноеКурьер', 'key' => 'ems_optimal_courier', 'price' => 'Доставка', 'max_weight' => 20000, 'p' => 'emoc'),
        array('name' => 'БизнесКурьер', 'key' => 'business_courier', 'price' => 'Доставка', 'max_weight' => 31500, 'p' => 'bc'),
        array('name' => 'БизнесКурьерЭкспресс', 'key' => 'business_courier_express', 'price' => 'Доставка', 'max_weight' => 31500, 'bce' => 'bc'),
        array('name' => 'МждМешокМ', 'key' => 'mzhd_meshok_m', 'price' => 'Тариф', 'max_weight' => 14500, 'p' => 'im'),
        array('name' => 'МждМешокМАвиа', 'key' => 'mzhd_meshok_m_avia', 'price' => 'Тариф', 'max_weight' => 14500, 'p' => 'ima'),
        array('name' => 'МждМешокМЗаказной', 'key' => 'mzhd_meshok_m_zakaznoi', 'price' => 'Тариф', 'max_weight' => 14500, 'p' => 'ir'),
        array('name' => 'МждМешокМАвиаЗаказной', 'key' => 'mzhd_meshok_m_avia_zakaznoi', 'price' => 'Тариф', 'max_weight' => 14500, 'p' => 'imar'),
        array('name' => 'МждБандероль', 'key' => 'mzhd_banderol', 'price' => 'Тариф', 'max_weight' => 5000, 'p' => 'ib'),
        array('name' => 'МждБандерольАвиа', 'key' => 'mzhd_banderol_avia', 'price' => 'Тариф', 'max_weight' => 5000, 'p' => 'iba'),
        array('name' => 'МждБандерольЗаказная', 'key' => 'mzhd_banderol_zakaznaya', 'price' => 'Тариф', 'max_weight' => 5000, 'p' => 'ibr'),
        array('name' => 'МждБандерольАвиаЗаказная', 'key' => 'mzhd_banderol_avia_zakaznaya', 'price' => 'Тариф', 'max_weight' => 5000, 'p' => 'ibar'),
        array('name' => 'МждМелкийПакет', 'key' => 'mzhd_paket', 'price' => 'Тариф', 'max_weight' => 2000, 'p' => 'is'),
        array('name' => 'МждМелкийПакетАвиа', 'key' => 'mzhd_paket_avia', 'price' => 'Тариф', 'max_weight' => 2000, 'p' => 'isa'),
        array('name' => 'МждМелкийПакетЗаказной', 'key' => 'mzhd_paket_zakaznoi', 'price' => 'Тариф', 'max_weight' => 2000, 'p' => 'isr'),
        array('name' => 'МждМелкийПакетАвиаЗаказной', 'key' => 'mzhd_paket_avia_zakaznoi', 'price' => 'Тариф', 'max_weight' => 2000, 'p' => 'isar'),
        array('name' => 'МждПосылка', 'key' => 'mzhd_posylka', 'price' => 'Тариф', 'max_weight' => 20000, 'p' => 'ip'),
        array('name' => 'МждПосылка', 'key' => 'mzhd_posylka_obyavlennaya_stennost', 'price' => 'Тариф', 'max_weight' => 20000, 'p' => 'ip'),
        array('name' => 'EMS_МждДокументы', 'key' => 'ems_mzhd_doc', 'price' => 'Тариф', 'max_weight' => 20000, 'p' => 'ied'),
        array('name' => 'EMS_МждТовары', 'key' => 'ems_mzhd_prod', 'price' => 'Тариф', 'max_weight' => 20000, 'p' => 'iem'),
        array('name' => 'МждПосылкаАвиа', 'key' => 'mzhd_posylka_avia', 'price' => 'Тариф', 'max_weight' => 20000, 'p' => 'ipa'),
        array('name' => 'МждПосылкаАвиа', 'key' => 'mzhd_posylka_avia_obyavlennaya_stennost', 'price' => 'Тариф', 'max_weight' => 20000, 'p' => 'ipa'),
    );

    public function __construct($registry) {
        //$this->db = $registry->get('db');
        //$this->config = $registry->get('config');
        //$this->session = $registry->get('session');
    }

    public function getShippingMethods() {
        return $this->methods;
    }
}
