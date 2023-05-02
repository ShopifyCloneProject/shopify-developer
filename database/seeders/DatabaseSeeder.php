<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            CollectionConditionsTableSeeder::class,
            ConditionTitlesTableSeeder::class,
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            VariantTableSeeder::class,
            TimeZoneTableSeeder::class,
            CurrencyTableSeeder::class,
            UserStoreIndustryTableSeeder::class,
            PaymentMethodTableSeeder::class,
            PaymentTypesTableSeeder::class,
            MethodTypesTableSeeder::class,
            WeightSeeder::class,
            CheckoutSeeder::class,
            PagesSeeder::class,
            ThemePageSeeder::class,
            MenuSeeder::class,
            SectionSeeder::class,
            XMLSeeder::class,
            NotificationsSeeder::class,
            DimensionsTableSeeder::class,
            LanguagesTableSeeder::class,
            CouriersTableSeeder::class,
            TrackingsTableSeeder::class,
            ShipmentsStatusTableSeeder::class,
            ShipOrdersTableSeeder::class,
            ShippingMethodsTableSeeder::class,
            ShippingDetailsTableSeeder::class,
            CountryTaxesTableSeeder::class,
            StateTaxesTableSeeder::class,
        ]);
    }
}
