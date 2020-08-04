<?php
/**
 * Created by PhpStorm.
 * User: Macbook
 * Date: 29.06.18
 * Time: 14:42
 */

class functions
{
	
	function searchShippingPayment($name, $value, $array) {
		foreach ($array as $key => $val) {
			if ($val[$name] === $value) {
				return $key;
			}
		}
		return null;
	}
	
	// Zkracování textu
	function checkStringLength($string, $MaxChar)
	{
		if ($MaxChar <= 1) return;

		$RetText = strip_tags($string);

		if (mb_strlen($RetText) <= $MaxChar)
			return $RetText;

		$okLength = $MaxChar;

		while (mb_substr($RetText, $okLength, 1) != " ")
			--$okLength;

		$RetText = mb_substr($RetText, 0, $okLength);

		return $RetText . "...";
	}

	// Odstranuje diaktritiku
	function removeInterpunction($string)
	{
		static $tbl = array("\xc3\xa1" => "a", "\xc3\xa4" => "a", "\xc4\x8d" => "c", "\xc4\x8f" => "d", "\xc3\xa9" => "e", "\xc4\x9b" => "e", "\xc3\xad" => "i", "\xc4\xbe" => "l", "\xc4\xba" => "l", "\xc5\x88" => "n", "\xc3\xb3" => "o", "\xc3\xb6" => "o", "\xc5\x91" => "o", "\xc3\xb4" => "o", "\xc5\x99" => "r", "\xc5\x95" => "r", "\xc5\xa1" => "s", "\xc5\xa5" => "t", "\xc3\xba" => "u", "\xc5\xaf" => "u", "\xc3\xbc" => "u", "\xc5\xb1" => "u", "\xc3\xbd" => "y", "\xc5\xbe" => "z", "\xc3\x81" => "A", "\xc3\x84" => "A", "\xc4\x8c" => "C", "\xc4\x8e" => "D", "\xc3\x89" => "E", "\xc4\x9a" => "E", "\xc3\x8d" => "I", "\xc4\xbd" => "L", "\xc4\xb9" => "L", "\xc5\x87" => "N", "\xc3\x93" => "O", "\xc3\x96" => "O", "\xc5\x90" => "O", "\xc3\x94" => "O", "\xc5\x98" => "R", "\xc5\x94" => "R", "\xc5\xa0" => "S", "\xc5\xa4" => "T", "\xc3\x9a" => "U", "\xc5\xae" => "U", "\xc3\x9c" => "U", "\xc5\xb0" => "U", "\xc3\x9d" => "Y", "\xc5\xbd" => "Z");

		return strtr($string, $tbl);
	}

	// Překoduje text na utf-8
	function getUTF8Text($string)
	{
		$RetText = html_entity_decode($string, ENT_QUOTES, 'UTF-8');

		return $RetText;
	}

	// Stáhne kurzovni listek - nutné nastavit cron
	public function downloadCNB()
	{

		$date = date('d.m.Y', time());
		$feed = file_get_contents("http://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt?date=$date");

		if ($feed == TRUE) {
			$file = fopen("./kurzy.txt", "w");
			fwrite($file, $feed);
			fclose($file);
		}

	}

	// Vrátí hodnotu kurzu
	public function cndFeed($currency)
	{
		$date = date('d.m.Y', time());
		$feed = file_get_contents("./kurzy.txt");
		$feed = explode('|', $feed);
		foreach ($feed as $key => $val) {
			if ($val == $currency) {
				$k = $key + 1;
				break;
			}
		}
		$kurz = explode(PHP_EOL, $feed[$k]);
		$value = $kurz[0];
		$value = str_replace(',', '.', $value);

		return $value;
	}
}
