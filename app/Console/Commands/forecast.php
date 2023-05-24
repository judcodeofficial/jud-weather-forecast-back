<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\AccuWeatherController;

class forecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:forecast {citiesNames?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve tabulated data of 5 day forecast by city names';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (is_null($this->argument('citiesNames')) || count($this->argument('citiesNames')) <= 0) {
            $inputCities = $this->ask('Please type the cities names: ');
            $citiesNames = explode(' ', $inputCities);
        } else {
            $citiesNames = $this->argument('citiesNames');
        }

        $tableList = [];

        foreach ($citiesNames as $cityName) {
            $getCountryData = (new AccuWeatherController)->FindForecastByCityName($cityName)->getData();
            if ($getCountryData->status == 'ok') {
                foreach ($getCountryData->data as $retrievedCity) {
                    $country = $retrievedCity->country_name;
                    $forecasts = $retrievedCity->DailyForecasts;
                    
                    $city = $cityName.' - '.$country;
                    $dayOne = 'Avg: '.(($forecasts[0]->minimum_temperature + $forecasts[0]->maximum_temperature)/2).' Max: '.$forecasts[0]->maximum_temperature.' Low: '.$forecasts[0]->minimum_temperature;
                    $dayTwo = 'Avg: '.(($forecasts[1]->minimum_temperature + $forecasts[1]->maximum_temperature)/2).' Max: '.$forecasts[1]->maximum_temperature.' Low: '.$forecasts[1]->minimum_temperature;
                    $dayThree = 'Avg: '.(($forecasts[2]->minimum_temperature + $forecasts[2]->maximum_temperature)/2).' Max: '.$forecasts[2]->maximum_temperature.' Low: '.$forecasts[2]->minimum_temperature;
                    $dayFour = 'Avg: '.(($forecasts[3]->minimum_temperature + $forecasts[3]->maximum_temperature)/2).' Max: '.$forecasts[3]->maximum_temperature.' Low: '.$forecasts[3]->minimum_temperature;
                    $dayFive = 'Avg: '.(($forecasts[4]->minimum_temperature + $forecasts[4]->maximum_temperature)/2).' Max: '.$forecasts[4]->maximum_temperature.' Low: '.$forecasts[4]->minimum_temperature;

                    $rowList = [];
                    array_push($rowList, $city);
                    array_push($rowList, $dayOne);
                    array_push($rowList, $dayTwo);
                    array_push($rowList, $dayThree);
                    array_push($rowList, $dayFour);
                    array_push($rowList, $dayFive);

                    array_push($tableList, $rowList);
                }
            } else {
                $this->error('An error occurred with city '.$cityName);
            }
        }

        $this->table(
            ['City', 'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'],
            $tableList
        );

    }
}
