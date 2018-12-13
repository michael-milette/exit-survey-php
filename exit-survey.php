<?php
// Exit Survey PHP is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Exit Survey PHP is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version information for Exit Survey PHP (also called ExitSurveyPHP).
 *
 * @package    exit-survey-php
 * @version    0.1
 * @copyright  2018 TNG Consulting Inc. - www.tngconsulting.ca
 * @author     Michael Milette
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//////////////////////////////////////////////////////////////////////////
// Status: ALPHA - this script is still in early development / testing. //
//////////////////////////////////////////////////////////////////////////

// Configuration Settings.

$mode = 2; // -1 = Reset Session, 0 = Off, 1 = On, 2 = Page views, 3 = Timer.
$option = 5; //Only applies to Mode 2 and 3 - Mode = 2: Page count, Mode = 3: Seconds.
// $option = rand ( 5, 10 ); // -- OR -- Optionally use a random number of page views or seconds within a range ($min, $max).
$reset = 5; // Page views or Minutes until mode is restarted once survey is displayed. Use 999999 for not in your lifetime.
// DOES NOT INCLUDE ANY SURVEY. Specify the URL of your survey on the next line.
$SurveyURL = "https://www.example.com";

// Uncomment to make session cookie persist beyond just this browser session.
//ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);  // 7 day cookie lifetime

$showSurvey=false;
session_start();

switch($mode) {
    case 0: // Reset session / Never.
        unset($_SESSION['survey_option']);
        break;
    case 1: // Always on.
        unset($_SESSION['survey_option']);
        $showSurvey=true;
        break;
    case 2: // After X page views. ($option = count of page views, $reset = reset).
        if(!isset($_SESSION['survey_option'])) {
            $_SESSION['survey_option'] = 1;
        }
        if($_SESSION['survey_option']++ >= $option) {
            $showSurvey = true;
            $_SESSION['survey_option'] = $option - $reset + 1;
        }
        break;
    case 3: // "After a specific amount of time. (option = elapsed seconds since initial view).
        if(!isset($_SESSION['survey_option'])) {
            $_SESSION['survey_option'] = time();
        }
        if($_SESSION['survey_option'] && time() - $_SESSION['survey_option'] >= $option) {
            $showSurvey = true;
            $_SESSION['survey_option'] = time() + $reset;
        }
        break;
}
?>
<html>
<head>
<!-- THIS IS AN EXAMPLE OF HOW TO IMPLEMENT Exit Survey PHP -->

<!-- Bootstrap compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<!-- Bootstrap compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <h1>Exit Survey PHP</h1>
    <p>Available options include:</p>
    <ol>
        <li>Always off.</li>
        <li>Always on.</li>
        <li>After X page views.</li>
        <li>When you load a page after X number of seconds.</li>
        <li>You can have the counter reset after X page views or X seconds or never.</li>
        <li>Page views or seconds can be a random number within a specified range.</li>
        <li>State of timer and counter can persiste beyond just this browser session.</li>
    </ol>
    <p>This page is currently set to display a popup survey every 5th time you reload it. The counter will automatically be reset if you close the browser.</p>
<?php
if($showSurvey) {
?>    
    <aside class="survey hidden-print">
        <style scoped="">.survey {z-index: 1000; position: relative; margin: 0 auto; margin: 0 15px;}#survey-close {height: 42px; line-height: 42px;}@media screen and (min-width: 480px) {.survey {position: fixed; bottom: 4.9em; left: 0; right: 0; margin: 0 auto; padding: 15px;}.survey .panel {box-shadow: 0 0 15px;}.survey .panel:focus {outline: 1px dotted #fff;}#survey-close {top: 15px; right: 15px;}}@media screen and (min-width: 480px) and (max-width: 767px) {.survey {margin: 0 15px;}#survey-close {right: 30px;}}@media screen and (min-width: 768px) {.survey {width: 720px;}}@media screen and (min-width: 992px) {.survey {width: 470px; left: 470px; bottom: 4.2em;}}@media screen and (min-width: 1200px) {.survey {width: 570px; left: 570px;}}html:not(.xxsmallview) .survey {position: fixed; bottom: 4.9em; left: 0; right: 0; margin: 0 auto; padding: 15px;}html:not(.xxsmallview) .survey .panel {box-shadow: 0 0 15px;}html:not(.xxsmallview) .survey .panel:focus {outline: 1px dotted #fff;}html:not(.xxsmallview) #survey-close {top: 15px; right: 15px;}html.xsmallview .survey {margin: 0 15px;}html.smallview .survey {width: 720px;}html.smallview #survey-close {right: 30px;}html.mediumview .survey {width: 470px; left: 470px; bottom: 4.2em;}html.largeview .survey, html.xlargeview .survey {width: 570px; left: 570px; bottom: 4.2em;}</style>
        <div class="panel panel-primary mrgn-bttm-0" tabindex="-1">
            <header class="panel-heading">
                <h2 class="panel-title">Exit survey</h2>
            </header>
            <div class="panel-body">
                <p>Please take a few minutes at the end of your visit today to anonymously tell us about your experience with this website.</p>
                <p class="mrgn-bttm-0">Choosing “Yes, after my visit” will open a new window or tab that you can return to once you complete your visit.</p>
                <ul class="list-inline mrgn-bttm-0">
                    <li class="mrgn-tp-md"><a id="survey-yes" class="btn btn-primary" href="<?php echo $SurveyURL; ?>" target="_blank"><strong>Yes, <span class="wb-inv">I will take the exit survey </span>after my visit<span class="wb-inv">.</span></strong></a></li>
                    <li class="mrgn-tp-md"><button id="survey-no" class="btn btn-default survey-close">No, <span class="wb-inv">I do not want to take the exit survey, </span>thank you<span class="wb-inv">.</span></button></li>
                </ul>
            </div>
            <button id="survey-close" class="mfp-close" title="Close: Exit survey (escape key)">×<span class="wb-inv"> Close: Exit survey (escape key)</span></button><input type="hidden" name="popupName" value="PP - ExitSurvey - 2017-06-06">
        </div>
    </aside>
<?php
}
?>
</body>
</html>
