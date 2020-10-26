<?php
namespace js\tools\numbers2words\languages;

use js\tools\numbers2words\exceptions\InvalidArgumentException;
use js\tools\numbers2words\Speller;

class Estonian extends Speller
{
	protected $minus = 'miinus';
	protected $decimalSeparator = ' ja ';
	
	protected function spellHundred($number, $groupOfThrees, $isDecimalPart, $currency)
	{
		static $hundreds = [
			1 => 'ükssada',
			2 => 'kakssada',
			3 => 'kolmsada',
			4 => 'nelisada',
			5 => 'viissada',
			6 => 'kuussada',
			7 => 'seitsesada',
			8 => 'kaheksasada',
			9 => 'üheksasada',
		];
		static $tens = [
			1 => 'kümme',
			2 => 'kakskümmend',
			3 => 'kolmkümmend',
			4 => 'nelikümmend',
			5 => 'viiskümmend',
			6 => 'kuuskümmend',
			7 => 'seitsekümmend',
			8 => 'kaheksakümmend',
			9 => 'üheksakümmend',
		];
		static $teens = [
			11 => 'üksteist',
			12 => 'kaksteist',
			13 => 'kolmteist',
			14 => 'neliteist',
			15 => 'viisteist',
			16 => 'kuusteist',
			17 => 'seitseteist',
			18 => 'kaheksateist',
			19 => 'üheksateist',
		];
		static $singles = [
			0 => 'null',
			1 => 'üks',
			2 => 'kaks',
			3 => 'kolm',
			4 => 'neli',
			5 => 'viis',
			6 => 'kuus',
			7 => 'seitse',
			8 => 'kaheksa',
			9 => 'üheksa',
		];
		
		$text = '';
		
		if ($number >= 100)
		{
			$text .= $hundreds[intval(substr("$number", 0, 1))];
			$number = $number % 100;
			
			if ($number === 0) // exact hundreds
			{
				return $text;
			}
			
			$text .= ' ';
		}
		
		if ($number < 10)
		{
			$text .= $singles[$number];
		}
		else if (($number > 10)
			&& ($number < 20))
		{
			$text .= $teens[$number];
		}
		else
		{
			$text .= $tens[intval(substr($number, 0, 1))];
			
			if ($number % 10 > 0)
			{
				$text .= ' ' . $singles[$number % 10];
			}
		}
		
		return $text;
	}
	
	protected function spellExponent($type, $number, $currency)
	{
		if ($type === 'million')
		{
			if ($number === 1)
			{
				return 'miljon';
			}
			
			return 'miljonit';
		}
		
		if ($type === 'thousand')
		{
			return 'tuhat';
		}
		
		return '';
	}
	
	protected function getCurrencyName($type, $number, $currency)
	{
		static $names = [
			self::CURRENCY_EURO           => [
				'whole'   => ['euro', 'eurot'],
				'decimal' => ['sent', 'senti'],
			],
			self::CURRENCY_BRITISH_POUND  => [
				'whole'   => ['nael', 'naela'],
				'decimal' => ['penn', 'penni'],
			],
			self::CURRENCY_LATVIAN_LAT    => [
				'whole'   => ['latt', 'latti'],
				'decimal' => ['santiim', 'santiimi'],
			],
			self::CURRENCY_LITHUANIAN_LIT => [
				'whole'   => ['litt', 'litti'],
				'decimal' => ['sent', 'senti'],
			],
			self::CURRENCY_RUSSIAN_ROUBLE => [
				'whole'   => ['rubla', 'rubla'],
				'decimal' => ['kopikas', 'kopikat'],
			],
			self::CURRENCY_US_DOLLAR      => [
				'whole'   => ['dollar', 'dollarit'],
				'decimal' => ['sent', 'senti'],
			],
			self::CURRENCY_PL_ZLOTY       => [
				'whole'   => ['zloty', 'zlote'],
				'decimal' => ['grosz', 'grosze'],
			],
		];
		
		if (!isset($names[$currency]))
		{
			throw new InvalidArgumentException('Unsupported currency');
		}
		
		$index = (($number === 1) ? 0 : 1);
		
		return $names[$currency][$type][$index];
	}
}
