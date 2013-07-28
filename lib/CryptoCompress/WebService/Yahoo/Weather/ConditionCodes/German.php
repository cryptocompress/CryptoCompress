<?php

namespace CryptoCompress\WebService\Yahoo\Weather\ConditionCodes;

class German {

	private static $t = array(
		4	=> 'Gewitter/Wind',
		30	=> 'teilweise bewölkt',
		32	=> 'Sonnig',
		33	=> 'überwiegend klar',
		34	=> 'heiter',
		39	=> 'nachmittags Gewitterschauer',
		#47	=> 'früh Gewitter',
	);

	public static function get($code, $text) {
		if (isset(self::$t[$code])) {
			return self::$t[$code];
		}

		#$t1 = self::conditions1($text);
		$t2 = self::conditions2($text);

		$logFile = '/var/log/twetter.log';
		if (is_writable($logFile)) {
			file_put_contents($logFile, $code . "\t=> '" . $t2 . "',\n" . $code . "\t=> '" . $text . "',\n", FILE_APPEND);
		}

		return $t2;
	}

	private static function conditions1($input) {
		if($input == "Light Rain") $data = "leichter Regen";
		elseif($input == "Haze") $data = "Dunst";
		elseif($input == "Unknown Precipitation") $data = "Niederschlag";
		elseif($input == "Partly Cloudy") $data = "teilweise bewölkt";
		elseif($input == "Cloudy")	 $data = "Bewölkt";
		elseif($input == "Mostly Cloudy") $data = "überwiegend bewölkt";
		elseif($input == "Blowing Snow") $data = "Schneetreiben";
		elseif($input == "Drizzle") $data = "Nieselregen";
		elseif($input == "Light Rain Shower") $data = "leichter Regenschauer";
		elseif($input == "Sunny") $data = "sonnig";
		elseif($input == "Fair") $data = "heiter";
		elseif($input == "Light Drizzle") $data = "leichter Nieselregen";
		elseif($input == "Wintry Mix") $data = "winterlicher Mix";
		elseif($input == "Clear") $data = "klar";
		elseif($input == "Light Snow") $data = "leichter Schneefall";
		elseif($input == "Fog") $data = "Nebel";
		elseif($input == "Mist") $data = "Nebel";
		elseif($input == "Showers in the Vicinity") $data = "Regenfälle in der Nähe";
		elseif($input == "Light Rain/Windy") $data = "leichter Regen/windig";
		elseif($input == "Rain and Snow") $data = "Schneeregen";
		elseif($input == "Light Snow") $data = "leichter Schneefall";
		elseif($input == "Snow") $data = "Schneefall";
		elseif($input == "Rain") $data = "Regen";
		elseif($input == "Mostly Clear") $data = "überwiegend klar";
		elseif($input == "Foggy") $data = "neblig";
		elseif($input == "Freezing Drizzle") $data = "gefrierender Nieselregen";
		elseif($input == "Freezing Rain") $data = "gefrierender Regen";
		elseif($input == "Mostly sunny") $data = "überwiegend sonnig";
		elseif($input == "Light Rain/Freezing Rain") $data = "leichter Regen/Gefrierender Regen";
		elseif($input == "Light Snow Shower") $data = "leichter Schneeschauer";
		elseif($input == "Ice Crystals") $data = "Eiskristalle";
		elseif($input == "Thunder") $data = "Gewitter";
		elseif($input == "Heavy Thunderstorm") $data = "Schweres Gewitter";
		elseif($input == "Thunderstorm") $data = "Gewitter";
		elseif($input == "Heavy Rain") $data = "starker Regen";
		elseif($input == "Light Rain with Thunder") $data = "leichter Regen mit Gewitter";
		elseif($input == "Thunder in the Vicinity") $data = "Gewitter in der Umgebung";
		elseif($input == "Partly Cloudy/Windy") $data = "teilweise bewölkt/windig";
		elseif($input == "Light Rain Shower/Windy") $data = "leichter Regenschauer/windig";
		elseif($input == "Patches of Fog") $data = "Nebelfelder";
		elseif($input == "Rain Shower") $data = "Regenschauer";
		elseif($input == "Light Freezing Rain") $data = "leichter gefrierender Regen";
		elseif($input == "Rain Shower/Windy") $data = "Regenschauer/windig";
		elseif($input == "Mostly Cloudy/Windy") $data = "Meist wolkig/windig";
		elseif($input == "Snow Shower") $data = "Schneeschauer";
		elseif($input == "Patches of Fog/Windy") $data = "Nebelfelder/windig";
		elseif($input == "Shallow Fog") $data = "flacher Nebel";
		elseif($input == "Cloudy/Windy") $data = "Wolkig/windig";
		elseif($input == "Light Snow/Fog") $data = "leichter Schneefall/Nebel";
		elseif($input == "Heavy Rain Shower") $data = "starker Regenschauer";
		elseif($input == "Light Rain Shower/Fog") $data = "leichter Regenschauer/Nebel";
		elseif($input == "Rain/Windy") $data = "Regen/windig";
		elseif($input == "Drizzle/Windy") $data = "Nieselregen/windig";
		elseif($input == "Heavy Drizzle") $data = "starker Nieselregen";
		elseif($input == "Squalls") $data = "Böen";
		elseif($input == "Heavy Thunderstorm/Windy") $data = "Schweres Gewitter/windig";
		elseif($input == "Snow Grains") $data = "Schneegriesel";
		elseif($input == "Partial Fog") $data = "teilweise Nebel";
		elseif($input == "Snow/Windy") $data = "Schnee/windig";
		elseif($input == "Fair/Windy") $data = "heiter/windig";
		elseif($input == "Heavy Snow/Windy") $data = "starker Schneefall/windig";
		elseif($input == "Heavy Snow") $data = "starker Schneefall";
		elseif($input == "Light Snow Shower/Fog") $data = "leichter Schneeschauer/Nebel";
		elseif($input == "Heavy Snow Shower") $data = "starker Schneeschauer";
		elseif($input == "Light Snow/Windy") $data = "leichter Schneeschauer/windig";
		elseif($input == "Drifting Snow/Windy") $data = "Schneetreiben/windig";
		elseif($input == "Light Snow/Windy/Fog") $data = "leichter Schneeschauer/windig/Nebel";
		elseif($input == "Freezing Drizzle/Windy") $data = "Gefrierender Nieselregen/windig";
		elseif($input == "Light Snow/Freezing Rain") $data = "leichter Schneefall/Gefrierender Regen";
		elseif($input == "Light Sleet") $data = "leichter Schneeregen";
		elseif($input == "Light Freezing Drizzle") $data = "leichter gefrierender Nieselregen";
		elseif($input == "Light Snow Grains") $data = "leichter Schneegriesel";
		elseif($input == "Clear/Windy") $data = "klar/windig";
		elseif($input == "Heavy Rain/Windy") $data = "starker Regen/windig";
		elseif($input == "Fog/Windy") $data = "Nebel/windig";
		elseif($input == "Unknown") $data = "unbekannt";
		elseif($input == "Sunny/Windy") $data = "sonnig/windig";
		elseif($input == "Sleet and Freezing Rain") $data = "Schneeregen und gefrierender Regen";
		elseif($input == "Clear/Windy") $data = "klar/windig";
		elseif($input == "Thunderstorm/Windy") $data = "Gewitter/windig";
		elseif($input == "Light Snow with Thunder") $data = "leichter Schneefall mit Gewitter";
		elseif($input == "Light Rain/Fog") $data = "leichter Regen/Nebel";
		elseif($input == "Light Snow/Windy/Fog") $data = "leichter Schneefall/windig/Nebel";
		elseif($input == "Sleet/Windy") $data = "Schneeregen/windig";
		elseif($input == "Rain and Snow/Windy ") $data = "Regen und Schnee/windig";
		elseif($input == "Fog/Windy") $data = "Nebel/windig";
		elseif($input == "Rain and Snow/Windy") $data = "Regen und Schnee/windig";
		elseif($input == "Light Freezing Rain/Fog") $data = "leichter gefrierender Regen/Nebel";
		elseif($input == "Drifting Snow") $data = "Schneetreiben";
		else $data = $input;

		return $data;
	}

	private static function conditions2($input) {
		if($input == "AM Clouds/PM Sun") $data = "vormittags bewölkt/nachmittags sonnig";
		elseif($input == "AM Drizzle") $data = "vormittags Nieselregen";
		elseif($input == "AM Drizzle/Wind") $data = "vormittags Nieselregen/Wind";
		elseif($input == "AM Fog/PM Clouds") $data = "vormittags Nebel/nachmittags bewölkt";
		elseif($input == "AM Fog/PM Sun") $data = "vormittags Nebel, nachmittags sonnig";
		elseif($input == "AM Ice") $data = "vormittags Eis";
		elseif($input == "AM Light Rain") $data = "vormittags leichter Regen";
		elseif($input == "AM Light Rain/Wind") $data = "vormittags leichter Regen/Wind";
		elseif($input == "AM Light Snow") $data = "vormittags leichter Schneefall";
		elseif($input == "AM Rain") $data = "vormittags Regen";
		elseif($input == "AM Rain/Snow Showers") $data = "vormittags Regen-/Schneeschauer";
		elseif($input == "AM Rain/Snow") $data = "vormittags Regen/Schnee";
		elseif($input == "AM Rain/Snow/Wind") $data = "vormittags Regen/Schnee/Wind";
		elseif($input == "AM Rain/Wind") $data = "vormittags Regen/Wind";
		elseif($input == "AM Showers") $data = "vormittags Schauer";
		elseif($input == "AM Showers/Wind") $data = "vormittags Schauer/Wind";
		elseif($input == "AM Snow Showers") $data = "vormittags Schneeschauer";
		elseif($input == "AM Snow") $data = "vormittags Schnee";
		elseif($input == "AM Thundershowers") $data = "vormittags Gewitterschauer";
		elseif($input == "Blowing Snow") $data = "Schneetreiben";
		elseif($input == "Clear") $data = "klar";
		elseif($input == "Clear/Windy") $data = "klar/windig";
		elseif($input == "Clouds Early/Clearing Late") $data = "früh Bewölkt/später klar";
		elseif($input == "Cloudy") $data = "Bewölkt";
		elseif($input == "Cloudy/Wind") $data = "Bewölkt/Wind";
		elseif($input == "Cloudy/Windy") $data = "Wolkig/windig";
		elseif($input == "Drifting Snow") $data = "Schneetreiben";
		elseif($input == "Drifting Snow/Windy") $data = "Schneetreiben/windig";
		elseif($input == "Drizzle Early") $data = "früh Nieselregen";
		elseif($input == "Drizzle Late") $data = "später Nieselregen";
		elseif($input == "Drizzle") $data = "Nieselregen";
		elseif($input == "Drizzle/Fog") $data = "Nieselregen/Nebel";
		elseif($input == "Drizzle/Wind") $data = "Nieselregen/Wind";
		elseif($input == "Drizzle/Windy") $data = "Nieselregen/windig";
		elseif($input == "Fair") $data = "heiter";
		elseif($input == "Fair/Windy") $data = "heiter/windig";
		elseif($input == "Few Showers") $data = "vereinzelte Schauer";
		elseif($input == "Few Showers/Wind") $data = "vereinzelte Schauer/Wind";
		elseif($input == "Few Snow Showers") $data = "vereinzelt Schneeschauer";
		elseif($input == "Fog Early/Clouds Late") $data = "früh Nebel, später Wolken";
		elseif($input == "Fog Late") $data = "später neblig";
		elseif($input == "Fog") $data = "Nebel";
		elseif($input == "Fog/Windy") $data = "Nebel/windig";
		elseif($input == "Foggy") $data = "neblig";
		elseif($input == "Freezing Drizzle") $data = "gefrierender Nieselregen";
		elseif($input == "Freezing Drizzle/Windy") $data = "gefrierender Nieselregen/windig";
		elseif($input == "Freezing Rain") $data = "gefrierender Regen";
		elseif($input == "Haze") $data = "Dunst";
		elseif($input == "Heavy Drizzle") $data = "starker Nieselregen";
		elseif($input == "Heavy Rain Shower") $data = "starker Regenschauer";
		elseif($input == "Heavy Rain") $data = "starker Regen";
		elseif($input == "Heavy Rain/Wind") $data = "starker Regen/Wind";
		elseif($input == "Heavy Rain/Windy") $data = "starker Regen/windig";
		elseif($input == "Heavy Snow Shower") $data = "starker Schneeschauer";
		elseif($input == "Heavy Snow") $data = "starker Schneefall";
		elseif($input == "Heavy Snow/Wind") $data = "starker Schneefall/Wind";
		elseif($input == "Heavy Thunderstorm") $data = "Schweres Gewitter";
		elseif($input == "Heavy Thunderstorm/Windy") $data = "Schweres Gewitter/windig";
		elseif($input == "Ice Crystals") $data = "Eiskristalle";
		elseif($input == "Ice Late") $data = "später Eis";
		elseif($input == "Isolated T-storms") $data = "Vereinzelte Gewitter";
		elseif($input == "Isolated Thunderstorms") $data = "Vereinzelte Gewitter";
		elseif($input == "Light Drizzle") $data = "leichter Nieselregen";
		elseif($input == "Light Freezing Drizzle") $data = "leichter gefrierender Nieselregen";
		elseif($input == "Light Freezing Rain") $data = "leichter gefrierender Regen";
		elseif($input == "Light Freezing Rain/Fog") $data = "leichter gefrierender Regen/Nebel";
		elseif($input == "Light Rain Early") $data = "anfangs leichter Regen";
		elseif($input == "Light Rain") $data = "leichter Regen";
		elseif($input == "Light Rain Late") $data = "später leichter Regen";
		elseif($input == "Light Rain Shower") $data = "leichter Regenschauer";
		elseif($input == "Light Rain Shower/Fog") $data = "leichter Regenschauer/Nebel";
		elseif($input == "Light Rain Shower/Windy") $data = "leichter Regenschauer/windig";
		elseif($input == "Light Rain with Thunder") $data = "leichter Regen mit Gewitter";
		elseif($input == "Light Rain/Fog") $data = "leichter Regen/Nebel";
		elseif($input == "Light Rain/Freezing Rain") $data = "leichter Regen/Gefrierender Regen";
		elseif($input == "Light Rain/Wind Early") $data = "früh leichter Regen/Wind";
		elseif($input == "Light Rain/Wind Late") $data = "später leichter Regen/Wind";
		elseif($input == "Light Rain/Wind") $data = "leichter Regen/Wind";
		elseif($input == "Light Rain/Windy") $data = "leichter Regen/windig";
		elseif($input == "Light Sleet") $data = "leichter Schneeregen";
		elseif($input == "Light Snow Early") $data = "früher leichter Schneefall";
		elseif($input == "Light Snow Grains") $data = "leichter Schneegriesel";
		elseif($input == "Light Snow Late") $data = "später leichter Schneefall";
		elseif($input == "Light Snow Shower") $data = "leichter Schneeschauer";
		elseif($input == "Light Snow Shower/Fog") $data = "leichter Schneeschauer/Nebel";
		elseif($input == "Light Snow with Thunder") $data = "leichter Schneefall mit Gewitter";
		elseif($input == "Light Snow") $data = "leichter Schneefall";
		elseif($input == "Light Snow/Fog") $data = "leichter Schneefall/Nebel";
		elseif($input == "Light Snow/Freezing Rain") $data = "leichter Schneefall/Gefrierender Regen";
		elseif($input == "Light Snow/Wind") $data = "leichter Schneefall/Wind";
		elseif($input == "Light Snow/Windy") $data = "leichter Schneeschauer/windig";
		elseif($input == "Light Snow/Windy/Fog") $data = "leichter Schneefall/windig/Nebel";
		elseif($input == "Mist") $data = "Nebel";
		elseif($input == "Mostly Clear") $data = "überwiegend klar";
		elseif($input == "Mostly Cloudy") $data = "überwiegend bewölkt";
		elseif($input == "Mostly Cloudy/Wind") $data = "meist bewölkt/Wind";
		elseif($input == "Mostly sunny") $data = "überwiegend sonnig";
		elseif($input == "Partial Fog") $data = "teilweise Nebel";
		elseif($input == "Partly Cloudy") $data = "teilweise bewölkt";
		elseif($input == "Partly Cloudy/Wind") $data = "teilweise bewölkt/Wind";
		elseif($input == "Patches of Fog") $data = "Nebelfelder";
		elseif($input == "Patches of Fog/Windy") $data = "Nebelfelder/windig";
		elseif($input == "PM Drizzle") $data = "nachmittags Nieselregen";
		elseif($input == "PM Fog") $data = "nachmittags Nebel";
		elseif($input == "PM Light Snow") $data = "nachmittags leichter Schneefall";
		elseif($input == "PM Light Rain") $data = "nachmittags leichter Regen";
		elseif($input == "PM Light Rain/Wind") $data = "nachmittags leichter Regen/Wind";
		elseif($input == "PM Light Snow/Wind") $data = "nachmittags leichter Schneefall/Wind";
		elseif($input == "PM Rain") $data = "nachmittags Regen";
		elseif($input == "PM Rain/Snow Showers") $data = "nachmittags Regen/Schneeschauer";
		elseif($input == "PM Rain/Snow") $data = "nachmittags Regen/Schnee";
		elseif($input == "PM Rain/Wind") $data = "nachmittags Regen/Wind";
		elseif($input == "PM Showers") $data = "nachmittags Schauer";
		elseif($input == "PM Showers/Wind") $data = "nachmittags Schauer/Wind";
		elseif($input == "PM Snow Showers") $data = "nachmittags Schneeschauer";
		elseif($input == "PM Snow Showers/Wind") $data = "nachmittags Schneeschauer/Wind";
		elseif($input == "PM Snow") $data = "nachmittags Schnee";
		elseif($input == "PM T-storms") $data = "nachmittags Gewitter";
		elseif($input == "PM Thundershowers") $data = "nachmittags Gewitterschauer";
		elseif($input == "PM Thunderstorms") $data = "nachmittags Gewitter";
		elseif($input == "Rain and Snow") $data = "Schneeregen";
		elseif($input == "Rain and Snow/Windy") $data = "Regen und Schnee/windig";
		elseif($input == "Rain/Snow Showers/Wind") $data = "Regen/Schneeschauer/Wind";
		elseif($input == "Rain Early") $data = "früh Regen";
		elseif($input == "Rain Late") $data = "später Regen";
		elseif($input == "Rain Shower") $data = "Regenschauer";
		elseif($input == "Rain Shower/Windy") $data = "Regenschauer/windig";
		elseif($input == "Rain to Snow") $data = "Regen, in Schnee übergehend";
		elseif($input == "Rain") $data = "Regen";
		elseif($input == "Rain/Snow Early") $data = "früh Regen/Schnee";
		elseif($input == "Rain/Snow Late") $data = "später Regen/Schnee";
		elseif($input == "Rain/Snow Showers Early") $data = "früh Regen-/Schneeschauer";
		elseif($input == "Rain/Snow Showers Late") $data = "später Regen-/Schneeschnauer";
		elseif($input == "Rain/Snow Showers") $data = "Regen/Schneeschauer";
		elseif($input == "Rain/Snow") $data = "Regen/Schnee";
		elseif($input == "Rain/Snow/Wind") $data = "Regen/Schnee/Wind";
		elseif($input == "Rain/Thunder") $data = "Regen/Gewitter";
		elseif($input == "Rain/Wind Early") $data = "früh Regen/Wind";
		elseif($input == "Rain/Wind Late") $data = "später Regen/Wind";
		elseif($input == "Rain/Wind") $data = "Regen/Wind";
		elseif($input == "Rain/Windy") $data = "Regen/windig";
		elseif($input == "Scattered Showers") $data = "vereinzelte Schauer";
		elseif($input == "Scattered Showers/Wind") $data = "vereinzelte Schauer/Wind";
		elseif($input == "Scattered Snow Showers") $data = "vereinzelte Schneeschauer";
		elseif($input == "Scattered Snow Showers/Wind") $data = "vereinzelte Schneeschauer/Wind";
		elseif($input == "Scattered T-storms") $data = "vereinzelte Gewitter";
		elseif($input == "Scattered Thunderstorms") $data = "vereinzelte Gewitter";
		elseif($input == "Shallow Fog") $data = "flacher Nebel";
		elseif($input == "Showers") $data = "Schauer";
		elseif($input == "Showers Early") $data = "früh Schauer";
		elseif($input == "Showers Late") $data = "später Schauer";
		elseif($input == "Showers in the Vicinity") $data = "Regenfälle in der Nähe";
		elseif($input == "Showers/Wind") $data = "Schauer/Wind";
		elseif($input == "Sleet and Freezing Rain") $data = "Schneeregen und gefrierender Regen";
		elseif($input == "Sleet/Windy") $data = "Schneeregen/windig";
		elseif($input == "Snow Grains") $data = "Schneegriesel";
		elseif($input == "Snow Late") $data = "später Schnee";
		elseif($input == "Snow Shower") $data = "Schneeschauer";
		elseif($input == "Snow Showers Early") $data = "früh Schneeschauer";
		elseif($input == "Snow Showers Late") $data = "später Schneeschauer";
		elseif($input == "Snow Showers") $data = "Schneeschauer";
		elseif($input == "Snow Showers/Wind") $data = "Schneeschauer/Wind";
		elseif($input == "Snow to Rain") $data = "Schneeregen";
		elseif($input == "Snow") $data = "Schneefall";
		elseif($input == "Snow/Wind") $data = "Schneefall/Wind";
		elseif($input == "Snow/Windy") $data = "Schnee/windig";
		elseif($input == "Squalls") $data = "Böen";
		elseif($input == "Sunny") $data = "sonnig";
		elseif($input == "Sunny/Wind") $data = "sonnig/Wind";
		elseif($input == "Sunny/Windy") $data = "sonnig/windig";
		elseif($input == "T-showers") $data = "Gewitterschauer";
		elseif($input == "Thunder in the Vicinity") $data = "Gewitter in der Umgebung";
		elseif($input == "Thunder") $data = "Gewitter";
		elseif($input == "Thundershowers Early") $data = "früh Gewitterschauer";
		elseif($input == "Thundershowers") $data = "Gewitterschauer";
		elseif($input == "Thunderstorm") $data = "Gewitter";
		elseif($input == "Thunderstorm/Windy") $data = "Gewitter/windig";
		elseif($input == "Thunderstorms Early") $data = "früh Gewitter";
		elseif($input == "Thunderstorms Late") $data = "später Gewitter";
		elseif($input == "Thunderstorms") $data = "Gewitter";
		elseif($input == "Unknown Precipitation") $data = "Niederschlag";
		elseif($input == "Unknown") $data = "unbekannt";
		elseif($input == "Wintry Mix") $data = "winterlicher Mix";
		else $data = $input;

		return $data;
	}

}
