<?php

function completeMonths($sToDate) {
   $aRetValue = array();

   //Max number of returned months!
   $iMaxMonths = 12;
   $iMonthCounter = 0;

   $iNow = time();
   $iCurrentYear = intval(date("Y", $iNow));
   $iCurrentMonth = intval(date("n", $iNow));
   $iCurrentDay = intval(date("j", $iNow));

   $iToYear = intval(substr($sToDate, 0, 4));
   $iToMonth = intval(substr($sToDate, 4, 2));
   $iToDay = intval(substr($sToDate, 6, 2));

   $iToDate = strtotime($iToYear . "-" . $iToMonth . "-" . $iToDay);
   /*print "<br>".date("Y - m - d", $iToDate)."<br>";*/
   /*

    $iToYear = intval(date("Y", $iToDate));
    $iToMonth = intval(date("n", $iToDate));
    $iToDay = intval(date("j", $iToDate));
    */

   if ($iCurrentYear == $iToYear) {
      for ($j = $iCurrentMonth; $j <= $iToMonth; $j++) {
         //First month
         if (($j == $iCurrentMonth) && ($iCurrentDay != 1)) {
            //Don't include first month or last month
            continue;
         }
         if (($j == $iToMonth) && ($iToDay != intval(date("t", $iToDate)))) {
            //Don't include first month or last month
            continue;
         }
         $aRetValue[] = _addMonth($iCurrentYear, $j);
      }
      return $aRetValue;
   }

   for ($i = $iCurrentYear; $i <= $iToYear; $i++) {
      if ($i == $iCurrentYear) {
         //First year
         for ($j = $iCurrentMonth; $j <= 12; $j++) {
            //First month
            if (($j != $iCurrentMonth) || ($iCurrentDay == 1)) {
               //Don't include first month
               $aRetValue[] = _addMonth($i, $j);
               $iMonthCounter++;
               if ($iMonthCounter >= $iMaxMonths) {
                  return $aRetValue;
               }
            }
         }
         continue;
      }
      if ($i == $iToYear) {
         //Last year
         for ($j = 1; $j <= $iToMonth; $j++) {
            //Last month
            if (($j != $iToMonth) || ($iToDay == intval(date("t", $iToDate)))) {
               //Don't include last month
               $aRetValue[] = _addMonth($i, $j);
               $iMonthCounter++;
               if ($iMonthCounter >= $iMaxMonths) {
                  return $aRetValue;
               }
            }
         }
         continue;
      }
      for ($j = 1; $j <= 12; $j++) {
         $aRetValue[] = _addMonth($i, $j);
         $iMonthCounter++;
         if ($iMonthCounter >= $iMaxMonths) {
            return $aRetValue;
         }
      }
   }
   return $aRetValue;
}

function _addMonth($year, $month) {
   return array(
      (($month > 9) ? '' : '0') . $month . $year,
      monthName($month) . ' ' . $year
   );
}

/**
 * Transforms SAP date to Mysql (Ex - 20090203 => 2009-02-03 )
 * @return string $dDateMysql

 */
function dateSapToMysql($sDateSap) {
   $dDateMysql = substr($sDateSap, 0, 4) . "-" . substr($sDateSap, 4, 2) . "-" . substr($sDateSap, 6, 2);
   return $dDateMysql;
}

/**
 * Transforms Mysql date to Sap (Ex - 2009-02-03 => 20090203 )
 * @return string $dDateMysql

 */
function dateMysqlToSap($sDateMysql) {
   return str_replace("-", "", $sDateMysql);
}

/**
 * Transforms SAP date to array (Ex: 20090203 => array("year" => 2009, "month" => 2, "day" => 3))
 * @return array
 * @param string $sDateSap
 */
function dateSapToArray($sDateSap) {
   $aResult = array();
   $aResult['year'] = intval(substr($sDateSap, 0, 4));
   $aResult['month'] = intval(substr($sDateSap, 4, 2));
   $aResult['day'] = intval(substr($sDateSap, 6, 2));
   return $aResult;
}

/**
 * Transforms SAP date to php date given a template like in date ex: d-m-Y
 * @return date
 * @param string $sDateSap
 */
function dateSapToPhp($sDateSap, $sFormat = 'Y-m-d') {
   if (($sDateSap == "") || ($sDateSap == "00000000")) {
      return null;
   }
   $aResult = array();
   $aResult['year'] = intval(substr($sDateSap, 0, 4));
   $aResult['month'] = intval(substr($sDateSap, 4, 2));
   $aResult['day'] = intval(substr($sDateSap, 6, 2));
   return date($sFormat, mktime(0, 0, 0, $aResult['month'], $aResult['day'], $aResult['year']));
}

/**
 * Transforms d.m.Y format to php date given template like in date ex: d-m-Y
 * @return date
 * @param string $sDate
 */
function dateBusinessToPhp($sDate, $sFormat = 'Y-m-d') {

   if ($sDate == "") {
      return null;
   }

   $aResult = array();
   $aResult['year'] = intval(substr($sDate, 6, 4));
   $aResult['month'] = intval(substr($sDate, 3, 2));
   $aResult['day'] = intval(substr($sDate, 0, 2));

   return date($sFormat, mktime(0, 0, 0, $aResult['month'], $aResult['day'], $aResult['year']));
}

/**
 * Transforms timestamp to sap date
 * @return string Sap date AAAALLZZ
 * @param int $iTimestamp[optional]
 */
function timestampToDateSap($iTimestamp = NULL) {
   if ($iTimestamp === NULL) {
      $iTimestamp = time();
   }
   $sYear = date("Y", $iTimestamp);
   $sMonth = date("m", $iTimestamp);
   $sDay = date("d", $iTimestamp);

   return $sYear . $sMonth . $sDay;
}

/**
 * Transforms array to SAP date(Ex: array("year" => 2009, "month" => 2, "day" => 3) => 20090203 )
 * @return string SAP formated date
 * @param array $aDate
 */
function arrayToDateSap($aDate) {
   $sMonth = (($aDate['month'] < 10) ? '0' : '') . $aDate['month'];
   $sDay = (($aDate['day'] < 10) ? '0' : '') . $aDate['day'];
   return $aDate['year'] . $sMonth . $sDay;
}

function monthName($iMonthNumber) {
   $aMonthNames = array(
      1 => "ianuarie",
      2 => "februarie",
      3 => "martie",
      4 => "aprilie",
      5 => "mai",
      6 => "iunie",
      7 => "iulie",
      8 => "august",
      9 => "septembrie",
      10 => "octombrie",
      11 => "noiembrie",
      12 => "decembrie"
   );
   return $aMonthNames[$iMonthNumber];
}

/**
 * Returns number of days between 2 SAP dates
 * @return int
 * @param string sDateStart
 * @param string sDataFinal
 */
function numberOfDaysBetween($sDateStart, $sDateFinal, $bStrict = FALSE) {
   $sYearStart = substr($sDateStart, 0, 4);
   $sMonthStart = substr($sDateStart, 4, 2);
   $sDayStart = substr($sDateStart, 6, 2);

   $sYearEnd = substr($sDateFinal, 0, 4);
   $sMonthEnd = substr($sDateFinal, 4, 2);
   $sDayEnd = substr($sDateFinal, 6, 2);
   $iDays = (mktime(0, 0, 0, $sMonthEnd, $sDayEnd, $sYearEnd) - mktime(0, 0, 0, $sMonthStart, $sDayStart, $sYearStart) ) / (60 * 60 * 24);
   if($bStrict === TRUE) {
      return $iDays;
   }
   return abs($iDays);
}

/**
 * Returns a date in format Y-m-d
 * @return int unix time
 * @param string interval
 * @param int $number
 * @param int $date unix date
 */
function DateAdd($interval, $number, $date) {

   $date_time_array = getdate($date);

   $hours = $date_time_array['hours'];
   $minutes = $date_time_array['minutes'];
   $seconds = $date_time_array['seconds'];
   $month = $date_time_array['mon'];
   $day = $date_time_array['mday'];
   $year = $date_time_array['year'];

   switch ($interval) {
      case 'yyyy' :
         $year += $number;
         break;
      case 'q' :
         $year += ($number * 3);
         break;
      case 'm' :
         $month += $number;
         break;
      case 'y' :
      case 'd' :
      case 'w' :
         $day += $number;
         break;
      case 'ww' :
         $day += ($number * 7);
         break;
      case 'h' :
         $hours += $number;
         break;
      case 'n' :
         $minutes += $number;
         break;
      case 's' :
         $seconds += $number;
         break;
   }
   $timestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
   return $timestamp;
}



/**
 * Returns array of mysql dates
 * @return int unix time
 * @param string interval
 * @param int $number
 * @param int $date unix date
 */
function daysBetween($sDateStart, $sDateFinal, $format = "Y-m-d") {

   $sYearStart = substr($sDateStart, 0, 4);
   $sMonthStart = substr($sDateStart, 4, 2);
   $sDayStart = substr($sDateStart, 6, 2);

   $iDaysBetween = numberOfDaysBetween($sDateStart, $sDateFinal, FALSE);

   for ($i = 0; $i <= $iDaysBetween; $i++) {
      $aFinalDates[] = date($format, DateAdd('d', $i, mktime(0, 0, 0, $sMonthStart, $sDayStart, $sYearStart)));
   }
   return $aFinalDates;
}

/**
 * Returns if a given value is date
 */
function _is_date($value, $format = 'yyyy-mm-dd') {
   if (strlen($value) == 10 && strlen($format) == 10) {
      // find separator. Remove all other characters from $format
      $separator_only = str_replace(array(
         'm',
         'd',
         'y'
      ), '', $format);
      $separator = $separator_only[0];
      // separator is first character

      if ($separator && strlen($separator_only) == 2) {
         // make regex

         $regexp = str_replace('mm', '[0-1][0-9]', $value);
         $regexp = str_replace('dd', '[0-3][0-9]', $value);
         $regexp = str_replace('yyyy', '[0-9]{4}', $value);
         $regexp = str_replace($separator, "\\" . $separator, $value);
         if ($regexp != $value && preg_match('/' . $regexp . '/', $value)) {
            // check date
            $day = substr($value, strpos($format, 'd'), 2);
            $month = substr($value, strpos($format, 'm'), 2);
            $year = substr($value, strpos($format, 'y'), 4);
            if (@checkdate($month, $day, $year))
               return true;
         }
      }
   }
   return false;
}

function getRoMonthNameFromNumber($iMonth) {
   $aMonths = array(
      'ianuarie',
      'februarie',
      'martie',
      'aprilie',
      'mai',
      'iunie',
      'iulie',
      'august',
      'septembrie',
      'octombrie',
      'noiembrie',
      'decembrie'
   );
   $iMonth = intval($iMonth);
   if (($iMonth < 1) || ($iMonth > 12)) {
      return '';
   }
   return $aMonths[$iMonth - 1];
}

/**
 * @param  $date1   - date start
 * @param  $date2   - date end
 * @return array
 */
function monthsBetween($date1, $date2) {

   $time1 = strtotime($date1);
   $time2 = strtotime($date2);
   $my = date('mY', $time2);

   $months = array(date("Y-m-d", $time1));
   if (date('mY', $time2) == date('mY', $time1)) {
      return $months;
   }

   while ($time1 < $time2) {

      $iDay = date('d', $time1);
      $incr = " +27 days";
      if ($iDay < 27) {
         $incr = " +1 month";
      }

      $time1 = strtotime(date('Y-m-d', $time1) . $incr);
      if (date('mY', $time1) != $my && ($time1 < $time2))
         $months[] = date("Y-m-d", $time1);
   }

   $months[] = date("Y-m-d", $time2);
   return $months;
}

//Extrage an, luna, zi din $sData in format MySQL (aaaa-ll-zz)
function date_segments($sData = NULL) {
   if ($sData === NULL) {
      $sData = date_mysql();
   }
   $segments = explode('-', $sData, 3);
   if (count($segments) < 3) {
      return FALSE;
   }
   $an = intval($segments[0]);
   if (strlen($an) != 4) {
      return FALSE;
   }
   $luna = intval($segments[1]);
   if (($luna < 1) || ($luna > 12)) {
      return FALSE;
   }
   $zi = intval($segments[2]);
   if (($zi < 1) || ($zi > days_in_month($luna, $an))) {
      return FALSE;
   }
   return array(
      $an,
      $luna,
      $zi
   );
}

//Intoarce timestamp obtinut din data mysql
function timestamp_from_mysql($sData = NULL) {
   $aRes = date_segments($sData);
   if ($aRes === FALSE) {
      return FALSE;
   }
   list($an, $luna, $zi) = $aRes;
   return mktime(0, 0, 0, $luna, $zi, $an);
}

//Intoarce $iTimestamp convertit la formatul MySQL (aaaa-ll-zz hh:mm:ss)
function datetime_mysql($iTimestamp = NULL) {
   if ($iTimestamp === NULL) {
      $iTimestamp = time();
   }
   return date('Y-m-d H:i:s', $iTimestamp);
}

//Intoarce $iTimestamp convertit la formatul MySQL (aaaa-ll-zz)
function date_mysql($iTimestamp = NULL) {
   if ($iTimestamp === NULL) {
      $iTimestamp = time();
   }
   return date('Y-m-d', $iTimestamp);
}

/**
 * Intoarce anul curent si anul viitor ( +descriere )
 * @return array
 */
function getAnGazier($sAcum = NULL) {
   list($an, $luna, ) = date_segments($sAcum);
   if ($luna <= 6) {
      $aAn["an_curent"] = $an - 1;
   }
   else {
      $aAn["an_curent"] = $an;
   }
   $aAn["an_curent_descr"] = "1 iulie " . $aAn["an_curent"] . " - 30 iunie " . ($aAn["an_curent"] + 1);
   $aAn["an_viitor"] = $aAn["an_curent"] + 1;
   $aAn["an_viitor_descr"] = "1 iulie " . $aAn["an_viitor"] . " - 30 iunie " . ($aAn["an_viitor"] + 1);
   $aAn["an_trecut"] = $aAn["an_curent"] - 1;
   $aAn["an_trecut_descr"] = "1 iulie " . $aAn["an_trecut"] . " - 30 iunie " . ($aAn["an_trecut"] + 1);
   return $aAn;
}

//Intoarce anul gazier din care face parte data precizata
function getAnGazierAsInt($sData) {
   list($an, $luna, ) = date_segments($sData);
   if ($luna <= 6) {
      return $an - 1;
   }
   return $an;
}

/* fiind data $startdate se cere ca sa se afle care e ziua lucratoare dupa $buisnessdays zile */
function add_business_days($startdate, $buisnessdays, $holidays, $dateformat = "Y-m-d") {
   $i = 0;
   $dayx = strtotime($startdate);
   $day = date('N', $dayx);

   while (($i < $buisnessdays) || ($day >= 6)) {
      $date = date('Y-m-d', $dayx);
      if ($day < 6 && !in_array($date, $holidays))
         $i++;
      $dayx = strtotime($date . ' +1 day');
      $day = date('N', $dayx);
   }
   return date($dateformat, $dayx);
}

function isWorkingDay($startdate, $dateformat = "Y-m-d") {
   if (is_null($startdate)) {
      return FALSE;
   }
   $dayx = strtotime($startdate);
   $day = date('N', $dayx);
   if ($day < 6) {
      return TRUE;
   }
   return FALSE;

}

function businessDate($dDate, $sReturnFormat = "d.m.Y") {
   if($dDate == "") {
      return null;
   }
   
   $dDate = strtotime($dDate);
   return date($sReturnFormat, $dDate);  
}
