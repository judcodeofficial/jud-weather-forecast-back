# Jud Weather Forecast - Backend
<p>This is the backend for the project, it is connected with the AccuWeatherApi to retrieve 5 days forecast depending on given city/cities.</p>

## Prerequisites
Before you can run the application, make sure you have the following created/installed:
1. An AccuweatherAPI account & ApiKey generated, if not you can create it here <a href="https://developer.accuweather.com/">AccuWeatherAPI</a>
2. PHP
3. npm
4. artisan

## Installation
<p>It's quite easy to install, follow this steps:</p>
<p>
    
1. Clone this repository to your local machine:
    
```bash
git clone https://github.com/judcodeofficial/jud-weather-forecast-back.git
```
    
2. Navigate to the project directory:

```bash  
cd jud-weather-forecast-back
```
    
3. Install required dependencies:
    
```bash  
npm install
```
    
4. Go to .env file, then set the AccuWeatherAPI Token:
<img src="https://github.com/judcodeofficial/jud-weather-forecast-back/assets/97370405/88d2415c-e6d4-49e3-bab9-ca733443a3db" />


5. Start server:
    
```bash  
php artisan serve
```

6. There you go, it's ready !
<img src="https://github.com/judcodeofficial/jud-weather-forecast-back/assets/97370405/02bbb273-4252-4fbb-bb5f-6779a5375896" />
</p>

## Test everything's okay
<p>
To test the connection between project and AccuWeatherAPI, you can execute the following command

(Replace CITY_NAME_N, for example sydney canberra medellin)

```bash  
php artisan app:forecast CITY_NAME_1 CITY_NAME_2 CITY_NAME_X
```

You can see an example in the following image:
</p>
<p>
<img src="https://raw.githubusercontent.com/judcodeofficial/jud-weather-forecast-back/main/resources/console.png" />
</p>

## Usage
<p>
1. You can see the available endpoints in 

```bash  
routes/api.php
```

2. Clone <a href="https://github.com/judcodeofficial/jud-weather-forecast-front">front-end project</a>
3. Try it
</p>

