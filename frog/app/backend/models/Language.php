<?php

/**
	Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
	Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class Language
{
	public static function all()
	{
		return $GLOBALS['iso_639_1'];
	}

	public static function nameOf($code)
	{
		if (isset($GLOBALS['iso_639_1'][$code]))
			return $GLOBALS['iso_639_1'][$code];

		return __('unknown');
	}
}

$GLOBALS['iso_639_1'] = array(
	'ab'=>'Abkhazian', // Абхазия
	'aa'=>'Afar', // Qafár af
	'af'=>'Afrikaans',
	'ak'=>'Akan',
	'sq'=>'Albanian',
	'am'=>'Amharic',
	'ar'=>'Arabic',
	'an'=>'Aragonese',
	'hy'=>'Armenian',
	'as'=>'Assamese',
	'av'=>'Avaric',
	'ae'=>'Avestan',
	'ay'=>'Aymara',
	'az'=>'Azerbaijani',
	'bm'=>'Bambara',
	'ba'=>'Bashkir',
	'eu'=>'Basque',
	'be'=>'Belarusian',
	'bn'=>'Bengali',
	'bh'=>'Bihari',
	'bi'=>'Bislama',
	'bs'=>'Bosnian',
	'br'=>'Breton',
	'bg'=>'Bulgarian',
	'my'=>'Burmese',
	'km'=>'Cambodian',
	'ca'=>'Catalan',
	'ch'=>'Chamorro',
	'ce'=>'Chechen',
	'zh'=>'Chinese',
	'cu'=>'Church Slavic',
	'cv'=>'Chuvash',
	'kw'=>'Cornish',
	'co'=>'Corsican',
	'cr'=>'Cree',
	'hr'=>'Croatian',
	'cs'=>'Czech',
	'da'=>'Danish',
	'dv'=>'Dhivehi',
	'nl'=>'Dutch',
	'dz'=>'Dzongkha',
	'en'=>'English',
	'eo'=>'Esperanto',
	'et'=>'Estonian',
	'ee'=>'Ewe',
	'fo'=>'Faroese',
	'fj'=>'Fijian',
	'fi'=>'Finnish',
	'fr'=>'Français', // French
	'ff'=>'Fulah',
	'gd'=>'Gaelic',
	'gl'=>'Galician',
	'lg'=>'Ganda',
	'ka'=>'Georgian',
	'de'=>'German',
	'el'=>'Greek',
	'kl'=>'Greenlandic',
	'gn'=>'Guarani',
	'gu'=>'Gujarati',
	'ht'=>'Haitian',
	'ha'=>'Hausa',
	'he'=>'Hebrew',
	'hz'=>'Herero',
	'hi'=>'Hindi',
	'ho'=>'Hiri Motu',
	'hu'=>'Hungarian', // t=Magyar
	'is'=>'Icelandic',
	'io'=>'Ido',
	'ig'=>'Igbo',
	'id'=>'Indonesian',
	'ia'=>'Interlingua',
	'ie'=>'Interlingue',
	'iu'=>'Inuktitut',
	'ik'=>'Inupiak',
	'ga'=>'Irish', // t=Gaeilge
	'it'=>'Italiano', // Italian
	'ja'=>'Japanese',
	'jv'=>'Javanese',
	'kn'=>'Kannada',
	'kr'=>'Kanuri',
	'ks'=>'Kashmiri',
	'kk'=>'Kazakh',
	'ki'=>'Kikuyu',
	'rw'=>'Kinyarwanda',
	'ky'=>'Kirghiz',
	'kv'=>'Komi',
	'kg'=>'Kongo',
	'ko'=>'Korean',
	'kj'=>'Kuanyama',
	'ku'=>'Kurdish',
	'lo'=>'Laothian',
	'la'=>'Latin', // 
	'lv'=>'Latvian',
	'li'=>'Limburgan',
	'ln'=>'Lingala',
	'lt'=>'Lithuanian',
	'lu'=>'Luba-Katanga',
	'lb'=>'Luxembourgish',
	'mk'=>'Macedonian',
	'mg'=>'Malagasy',
	'ms'=>'Malay',
	'ml'=>'Malayalam',
	'mt'=>'Maltese',
	'gv'=>'Manx',
	'mi'=>'Maori',
	'mr'=>'Marathi',
	'mh'=>'Marshallese',
	'mo'=>'Moldavian',
	'mn'=>'Mongolian',
	'na'=>'Nauru',
	'nv'=>'Navajo',
	'ng'=>'Ndonga',
	'ne'=>'Nepali',
	'nd'=>'North Ndebele',
	'se'=>'Northern Sami',
	'no'=>'Norwegian',
	'nb'=>'Norwegian Bokmal',
	'nn'=>'Norwegian Nynorsk',
	'ny'=>'Nyanja',
	'oc'=>'Occitan',
	'oj'=>'Ojibwa',
	'or'=>'Oriya',
	'om'=>'Oromo',
	'os'=>'Ossetian',
	'pi'=>'Pali',
	'fa'=>'Persian', // 
	'pl'=>'Polish', // 
	'pt'=>'Portuguese', // 
	'pa'=>'Punjabi',
	'ps'=>'Pushto',
	'qu'=>'Quechua',
	'ro'=>'Romanian', // 
	'rm'=>'Romansh', // 
	'rn'=>'Rundi',
	'ru'=>'Russian', // 
	'sm'=>'Samoan',
	'sg'=>'Sangro',
	'sa'=>'Sanskrit',
	'sc'=>'Sardinian',
	'sr'=>'Serbian', // 
	'sh'=>'Serbo-Croatian',
	'st'=>'Sesotho',
	'sn'=>'Shona',
	'ii'=>'Sichuan Yi',
	'sd'=>'Sindhi',
	'si'=>'Sinhala',
	'ss'=>'Siswati',
	'sk'=>'Slovak', // 
	'sl'=>'Slovenian', // 
	'so'=>'Somali',
	'nr'=>'South Ndebele',
	'es'=>'Spanish', // 
	'su'=>'Sudanese',
	'sw'=>'Swahili',
	'sv'=>'Swedish', // 
	'tl'=>'Tagalog',
	'ty'=>'Tahitian',
	'tg'=>'Tajik',
	'ta'=>'Tamil',
	'tt'=>'Tatar',
	'te'=>'Tegulu',
	'th'=>'Thai', // 
	'bo'=>'Tibetan', // 
	'ti'=>'Tigrinya',
	'to'=>'Tonga',
	'ts'=>'Tsonga',
	'tn'=>'Tswana',
	'tr'=>'Turkish', // 
	'tk'=>'Turkmen',
	'tw'=>'Twi',
	'ug'=>'Uighur',
	'uk'=>'Ukrainian', // 
	'ur'=>'Urdu',
	'uz'=>'Uzbek',
	've'=>'Venda',
	'vi'=>'Vietnamese', // 
	'vo'=>'Volapuk',
	'wa'=>'Walloon',
	'cy'=>'Welsh',
	'fy'=>'Western Frisian',
	'wo'=>'Wolof',
	'xh'=>'Xhosa',
	'yi'=>'Yiddish',
	'yo'=>'Yoruba',
	'za'=>'Zhuang',
	'zu'=>'Zulu');
