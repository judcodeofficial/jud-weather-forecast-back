<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use App\Models\CustomResponse;
use App\Models\DailyForecast;

class AccuWeatherController extends Controller
{
    private $apiBaseUrl = '';
    private $apiKey = '';

    public function __construct() {
        $this->apiBaseUrl = env('ACCUWEATHER_API_URL');
        $this->apiKey = env('ACCUWEATHER_API_KEY');
    }

    public function FindForecastByCityName($cityName){
        $response = new CustomResponse();
        $requestCities = $this->FindCityByName($cityName)->getData();

        if ($requestCities->status == 'ok') {
            $findedCities = $requestCities->data;
            $responseData = [];

            foreach ($findedCities as $findedCity) {
                $cityKey = $findedCity->Key;
                $cityName = $findedCity->LocalizedName;
                $cityCountry = $findedCity->Country->LocalizedName;

                $requestForecast = $this->FindForecastByCityKey($cityKey)->getData();
                if ($requestForecast->status == 'ok') {
                    $requestForecast->data->city_name = $cityName;
                    $requestForecast->data->country_name = $cityCountry;
                    array_push($responseData, $requestForecast->data);
                }
                $response->SetOk($responseData);
            }
        }

        return response()->json($response);
    }

    public function FindForecastByCityKey($cityKey){
        $response = $this->FindCityByKey($cityKey)->getData();
        
        if ($response->status == 'ok') {
            $cityInfo = $response->data;
            $endpointUrl = $this->apiBaseUrl.'/forecasts/v1/daily/5day/'.$cityKey.'?apikey='.$this->apiKey;

            $response = new CustomResponse();

            $responseRequest = $this->GetRequest($endpointUrl);

            if (!is_null($responseRequest) && !empty($responseRequest) || (array_key_exists('Code', $responseRequest) && $responseRequest['Code'] != 'ServiceUnavailable')) {
                $dailyForecastsList = [
                    'locationName' => $cityInfo->LocalizedName,
                    'minimum_avg' => 0,
                    'maximum_avg' => 0,
                    'DailyForecasts' => []
                ];

                $minimumAvg = 0;
                $maximumAvg = 0;
                foreach ($responseRequest['DailyForecasts'] as $dailyForecast) {
                    $newDailyForecast = new DailyForecast();
                    $newDailyForecast->date = $dailyForecast['Date'];
                    $newDailyForecast->minimum_temperature = $dailyForecast['Temperature']['Minimum']['Value'];
                    $newDailyForecast->maximum_temperature = $dailyForecast['Temperature']['Maximum']['Value'];

                    $minimumAvg += $newDailyForecast->minimum_temperature;
                    $maximumAvg += $newDailyForecast->maximum_temperature;

                    array_push($dailyForecastsList['DailyForecasts'], $newDailyForecast);
                }

                $dailyForecastsList['minimum_avg'] = $minimumAvg / 5;
                $dailyForecastsList['maximum_avg'] = $maximumAvg / 5;

                $response->SetOk($dailyForecastsList , '');
            }
            else {
                $response->SetNotFound();
            }
        }

        return response()->json($response);
    }

    public function FindCitiesList(){
        $endpointUrl = $this->apiBaseUrl."/locations/v1/topcities/50?apikey=".$this->apiKey;
        $response = new CustomResponse();
        $responseRequest = $this->GetRequest($endpointUrl);

        if (!is_null($responseRequest) && !empty($responseRequest) || (array_key_exists('Code', $responseRequest) && $responseRequest['Code'] != 'ServiceUnavailable')) {
            $response->SetOk($responseRequest , '');
        }
        else {
            $response->SetNotFound();
        }

        return response()->json($response);
    }

    private function FindCityByName($cityName){
        $endpointUrl = $this->apiBaseUrl."/locations/v1/cities/search?q=";
        $response = new CustomResponse();

        if (is_null($cityName) || empty(trim($cityName))) {
            $response->SetError('You must type a name');
            return $response;
        }

        $endpointUrl .= $cityName.'&apikey='.$this->apiKey;

        $responseRequest = $this->GetRequest($endpointUrl);

        if (!is_null($responseRequest) && !empty($responseRequest) || (array_key_exists('Code', $responseRequest) && $responseRequest['Code'] != 'ServiceUnavailable')) {
            $response->SetOk($responseRequest , '');
        }
        else {
            $response->SetNotFound();
        }


        return response()->json($response);
    }

    private function FindCityByKey($cityKey){
        $endpointUrl = $this->apiBaseUrl."/locations/v1/";
        $response = new CustomResponse();

        if (is_null($cityKey) || empty(trim($cityKey))) {
            $response->SetError('You must type a key');
            return $response;
        }

        $endpointUrl .= $cityKey.'?apikey='.$this->apiKey;

        $responseRequest = $this->GetRequest($endpointUrl);

        if (!is_null($responseRequest) && !empty($responseRequest) || (array_key_exists('Code', $responseRequest) && $responseRequest['Code'] != 'ServiceUnavailable')) {
            $response->SetOk($responseRequest , '');
        }
        else {
            $response->SetNotFound();
        }


        return response()->json($response);
    }

    public function GetRequest($endpointUrl){
        $httpRequest = Http::get($endpointUrl);
        $jsonHttpRequest = $httpRequest->json();

        if (is_null($jsonHttpRequest) || empty($jsonHttpRequest) || (array_key_exists('Code', $jsonHttpRequest) && $jsonHttpRequest['Code'] != 'ServiceUnavailable')){
            throw new \ErrorException('It seems like the API Key is expired or the max attemps per day have been reached.');
        }

        return $httpRequest->json();
    }
}
