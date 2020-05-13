<?php

require_once 'common.php';


include_once 'keyring/api.google.include.php';

function initializeAnalytics($key) {

    $client = new Google_Client();
    $client->setApplicationName("Aurora Analytics");
    $client->setAuthConfig($key);
    $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
    $analytics = new Google_Service_AnalyticsReporting($client);

    return $analytics;
}

function initializeSearch($key) {

    $client = new Google_Client();
    $client->setApplicationName("Aurora Search Console");
    $client->setAuthConfig($key);
    $client->setScopes(['https://www.googleapis.com/auth/webmasters.readonly']);
    $webmastersService = new Google_Service_Webmasters($client);

    return $webmastersService;
}

function getReport($analytics, $view, $website, $date, $device) {
    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate($date["fromDate"]);
    $dateRange->setEndDate($date["toDate"]);

    if ($device != "all") {
        $pageviews = new Google_Service_AnalyticsReporting_Metric();
        $pageviews->setExpression('ga:pageviews');
        $pageviews->setAlias('Pageviews');
        $users = new Google_Service_AnalyticsReporting_Metric();
        $users->setExpression("ga:users");
        $users->setAlias("Users");
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("Sessions");
        $pageValue = new Google_Service_AnalyticsReporting_Metric();
        $pageValue->setExpression("ga:pageValue");
        $pageValue->setAlias("Page Value");
    } else {
        $pageValue = new Google_Service_AnalyticsReporting_Metric();
        $pageValue->setExpression("ga:pageValue");
        $pageValue->setAlias("Page Value");
    }

    $hostname = new Google_Service_AnalyticsReporting_Dimension();
    $hostname->setName("ga:hostname");
    $pagePath = new Google_Service_AnalyticsReporting_Dimension();
    $pagePath->setName("ga:pagePath");

    $segmentDimensions = new Google_Service_AnalyticsReporting_Dimension();
    $segmentDimensions->setName("ga:segment");

    $dimensionFilter = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
    $dimensionFilter->setDimensionName("ga:hostname");
    $dimensionFilter->setOperator("EXACT");
    $dimensionFilter->setExpressions(array($website));

    $segmentFilterClause = new Google_Service_AnalyticsReporting_SegmentFilterClause();
    $segmentFilterClause->setDimensionFilter($dimensionFilter);

    $orFiltersForSegment = new Google_Service_AnalyticsReporting_OrFiltersForSegment();
    $orFiltersForSegment->setSegmentFilterClauses(array($segmentFilterClause));

    if ($device != "all") {

        $dimensionDeviceFilter = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $dimensionDeviceFilter->setDimensionName("ga:deviceCategory");
        $dimensionDeviceFilter->setOperator("EXACT");
        $dimensionDeviceFilter->setExpressions(array($device));

        $segmentDeviceFilterClause = new Google_Service_AnalyticsReporting_SegmentFilterClause();
        $segmentDeviceFilterClause->setDimensionFilter($dimensionDeviceFilter);

        $orFiltersForDeviceSegment = new Google_Service_AnalyticsReporting_OrFiltersForSegment();
        $orFiltersForDeviceSegment->setSegmentFilterClauses(array($segmentDeviceFilterClause));

        $simpleSegment = new Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment,$orFiltersForDeviceSegment));
    } else {

        $simpleSegment = new Google_Service_AnalyticsReporting_SimpleSegment();
        $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment));
    }

    $segmentFilter = new Google_Service_AnalyticsReporting_SegmentFilter();
    $segmentFilter->setSimpleSegment($simpleSegment);

    $segmentDefinition = new Google_Service_AnalyticsReporting_SegmentDefinition();
    $segmentDefinition->setSegmentFilters(array($segmentFilter));

    $dynamicSegment = new Google_Service_AnalyticsReporting_DynamicSegment();
    $dynamicSegment->setSessionSegment($segmentDefinition);
    $dynamicSegment->setName("website");

    $segment = new Google_Service_AnalyticsReporting_Segment();
    $segment->setDynamicSegment($dynamicSegment);


    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($view);
    $request->setDateRanges($dateRange);
    $request->setDimensions(array($hostname,$pagePath,$segmentDimensions));
    $request->setSegments(array($segment));
    if ($device != "all") {
        $request->setMetrics(array($pageValue,$pageviews,$users,$sessions));
    } else {
        $request->setMetrics(array($pageValue));
    }
    $request->setPageSize(10000);

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

function getSearchReport($webmastersService, $website, $dateRange) {

    $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
    $query->setDimensions(array('page'));
    $query->setStartDate($dateRange["fromDate"]);
    $query->setEndDate($dateRange["toDate"]);
    $query->setRowLimit(5000);

    $report = $webmastersService->searchanalytics->query('https://'.$website.'/', $query);
    return $report;
}

function getQueryReport($webmastersService, $website, $dateRange) {

    $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
    $query->setDimensions(array('page','query'));
    $query->setStartDate($dateRange["fromDate"]);
    $query->setEndDate($dateRange["toDate"]);
    $query->setRowLimit(5000);

    $report = $webmastersService->searchanalytics->query('https://'.$website.'/', $query);
    return $report;
}
function dbReportInsert($rows, $range, $dev, $apiCallId){
    global $db;
    $sql = "";
    foreach ($rows as $r) {
        $dimensions = $r->getDimensions();
        $metrics = $r->getMetrics();
        $values = $metrics[0]->getValues();
        $hostname = $dimensions[0];
        $pagePath = $dimensions[1];
        $path = parse_url($pagePath, PHP_URL_PATH);
        $pageValue = $values[0];
        $pageviews = array_key_exists(1,$values)?$values[1]:0;
        $users = array_key_exists(2,$values)?$values[2]:0;
        $sessions = array_key_exists(3,$values)?$values[3]:0;
        $device = $dev == 'all'?"":" ".ucfirst($dev);

        if ($dev != "all") {
            $sql .= "INSERT IGNORE INTO kbase.`Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('https://" . $hostname . $pagePath . "' ,'$hostname' ,'$pagePath' ,'$path');
            INSERT INTO kbase.`Google Webpage Data` (`Google API Call Key`, `Google Webpage Key`, `Google Webpage $range$device Page Value`, `Google Webpage $range$device Pageviews`, `Google Webpage $range$device Sessions`, `Google Webpage $range$device Users`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM kbase.`Google Webpage` WHERE `Google Webpage URL` = 'https://"
                . $hostname . $pagePath
                . "') ,'$pageValue' ,'$pageviews' ,'$sessions' ,'$users') ON DUPLICATE KEY UPDATE `Google Webpage $range$device Page Value` = '$pageValue', `Google Webpage $range$device Pageviews` = '$pageviews', `Google Webpage $range$device Sessions` = '$sessions', `Google Webpage $range$device Users` = '$users' ;";
        } else {
            $sql .= "INSERT IGNORE INTO kbase.`Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('https://" . $hostname . $pagePath . "' ,'$hostname' ,'$pagePath' ,'$path');
            INSERT INTO kbase.`Google Webpage Data` (`Google API Call Key`, `Google Webpage Key`, `Google Webpage $range Page Value`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM kbase.`Google Webpage` WHERE `Google Webpage URL` = 'https://"
                . $hostname . $pagePath
                . "') ,'$pageValue') ON DUPLICATE KEY UPDATE `Google Webpage $range Page Value` = '$pageValue' ;";
        }
    }
    if ($sql != "") {
        $db->exec($sql);
    }
}
function dbSearchInsert($responseSearch, $responseQuery, $range, $apiCallId) {
    global $db;
    $sql = "";
    foreach ($responseSearch as $r) {
        $hostname = parse_url($r->keys[0], PHP_URL_HOST);
        $path     = parse_url($r->keys[0], PHP_URL_PATH);
        $query    = parse_url($r->keys[0], PHP_URL_QUERY) !== null ? '?'.parse_url($r->keys[0], PHP_URL_QUERY) : '';
        $fragment = parse_url($r->keys[0], PHP_URL_FRAGMENT) !== null ? '#'.parse_url($r->keys[0], PHP_URL_FRAGMENT) : '';

        $sql .= "INSERT IGNORE INTO kbase.`Google Webpage` (`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('".$r->keys[0]."' ,'$hostname' ,'".$path.$query.$fragment."' ,'$path');
            INSERT INTO kbase.`Google Webpage Data` (`Google API Call Key`, `Google Webpage Key`, `Google Webpage $range Clicks`, `Google Webpage $range CTR`, `Google Webpage $range Impressions`, `Google Webpage $range Position`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM kbase.`Google Webpage` WHERE `Google Webpage URL` = '"
            .$r->keys[0]
            ."'),'$r->clicks' ,'$r->ctr' ,'$r->impressions' , '$r->position') ON DUPLICATE KEY UPDATE `Google Webpage $range Clicks` = '$r->clicks', `Google Webpage $range CTR` = '$r->ctr', `Google Webpage $range Impressions` = '$r->impressions', `Google Webpage $range Position` = '$r->position' ;";
    }
    foreach ($responseQuery as $q) {
        $hostname = parse_url($q->keys[0], PHP_URL_HOST);
        $path     = parse_url($q->keys[0], PHP_URL_PATH);
        $query    = parse_url($q->keys[0], PHP_URL_QUERY) !== null ? '?'.parse_url($q->keys[0], PHP_URL_QUERY) : '';
        $fragment = parse_url($q->keys[0], PHP_URL_FRAGMENT) !== null ? '#'.parse_url($q->keys[0], PHP_URL_FRAGMENT) : '';

        $sql .= "INSERT IGNORE INTO kbase.`Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('".$r->keys[0]."' ,'$hostname' ,'".$path.$query.$fragment."' ,'$path');
            INSERT IGNORE INTO kbase.`Google Query`(`Google Query`) VALUES ('".$q->keys[1]."');
            INSERT INTO kbase.`Google Query Data` (`Google API Call Key`, `Google Webpage Key`, `Google Query Key`, `Google Query $range Clicks`, `Google Query $range CTR`, `Google Query $range Impressions`, `Google Query $range Position`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM kbase.`Google Webpage` WHERE `Google Webpage URL` = '"
            .$q->keys[0]."'),(SELECT `Google Query Key` FROM kbase.`Google Query` WHERE `Google Query` = '".$q->keys[1]
            ."'),'$q->clicks' ,'$q->ctr' ,'$q->impressions' , '$q->position') ON DUPLICATE KEY UPDATE `Google Query $range Clicks` = '$q->clicks', `Google Query $range CTR` = '$q->ctr', `Google Query $range Impressions` = '$q->impressions', `Google Query $range Position` = '$q->position';";
    }
    if ($sql != "") {
        $db->exec($sql);
    }
}

function dbDailyInsert($rowsDaily, $responseDailySearch, $apiCallId, $parameter) {
    global $db;
    $sql = "";

    foreach ($rowsDaily as $rd) {
        $dimensions = $rd->getDimensions();
        $metrics    = $rd->getMetrics();
        $values     = $metrics[0]->getValues();
        $hostname   = $dimensions[0];
        $pagePath   = $dimensions[1];
        $path       = parse_url($pagePath, PHP_URL_PATH);
        $pageValue  = $values[0];
        $pageviews  = $values[1];
        $users      = $values[2];
        $sessions   = $values[3];

        $sql .= "INSERT IGNORE INTO kbase.`Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('https://".$hostname.$pagePath."' ,'$hostname' ,'$pagePath' ,'$path');
            INSERT INTO kbase.`Google Time Series Data`(`Google API Call Key`, `Google Webpage Key`, `Google Time Series Date`, `Google Time Series Page Value`, `Google Time Series Pageviews`, `Google Time Series Sessions`, `Google Time Series Users`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM kbase.`Google Webpage` WHERE `Google Webpage URL` = 'https://"
            .$hostname.$pagePath."') ,'".$parameter->daily["fromDate"]
            ."' ,'$pageValue' ,'$pageviews' ,'$sessions' ,'$users') ON DUPLICATE KEY UPDATE `Google Time Series Page Value` = '$pageValue', `Google Time Series Pageviews` = '$pageviews', `Google Time Series Sessions` = '$sessions', `Google Time Series Users` = '$users' ;";
    }
    foreach ($responseDailySearch as $rq) {
        $hostname = parse_url($rq->keys[0], PHP_URL_HOST);
        $path     = parse_url($rq->keys[0], PHP_URL_PATH);
        $query    = parse_url($rq->keys[0], PHP_URL_QUERY) !== null ? '?'.parse_url($rq->keys[0], PHP_URL_QUERY) : '';
        $fragment = parse_url($rq->keys[0], PHP_URL_FRAGMENT) !== null ? '#'.parse_url($rq->keys[0], PHP_URL_FRAGMENT) : '';

        $sql .= "INSERT IGNORE INTO kbase.`Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('".$rq->keys[0]."' ,'$hostname' ,'".$path.$query.$fragment."' ,'$path');
            INSERT INTO kbase.`Google Time Series Data`(`Google API Call Key`, `Google Webpage Key`, `Google Time Series Date`, `Google Time Series Clicks`, `Google Time Series CTR`, `Google Time Series Impressions`, `Google Time Series Position`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM kbase.`Google Webpage` WHERE `Google Webpage URL` = '"
            .$rq->keys[0]."') ,'".$parameter->daily["fromDate"]
            ."' ,'$rq->clicks' ,'$rq->ctr' ,'$rq->impressions' , '$rq->position') ON DUPLICATE KEY UPDATE `Google Time Series Clicks` = '$rq->clicks', `Google Time Series CTR` = '$rq->ctr', `Google Time Series Impressions` = '$rq->impressions', `Google Time Series Position` = '$rq->position' ;";
    }
    if ($sql != "") {
        $db->exec($sql);
    }
}


//echo "Start Time :".date("Y-m-d  H:i:s", time())."\n";

$executionSqlStart = "INSERT INTO kbase.`Google API Call Dimension` (`Google API Call Start Date`, `Google API Call Details`) VALUES ('".date("Y-m-d  H:i:s", time())."','{}');";
$db->exec($executionSqlStart);
$apiCallId = $db->lastInsertId();
//echo "API Call ID :".$apiCallId."\n";

foreach ($apiInclude->property as $k => $pa) {
    $DateDailySqlUpdate = "UPDATE kbase.`Google API Call Dimension` SET `Google API Call Details` = JSON_INSERT(`Google API Call Details` ,'$.\"".$apiInclude->daily["fromDate"].":".$apiInclude->daily["toDate"]."\"' ,JSON_ARRAY()) WHERE `Google API Call Key` = ?;";
    $db->prepare($DateDailySqlUpdate)->execute([$apiCallId]);

    $analytics         = initializeAnalytics($apiInclude->key);
    $webmastersService = initializeSearch($apiInclude->key);
    foreach ($apiInclude->dateRange as $range => $dateRange) {

        $DateSqlUpdate = "UPDATE kbase.`Google API Call Dimension` SET `Google API Call Details` = JSON_INSERT(`Google API Call Details` ,'$.\"".$dateRange["fromDate"].":".$dateRange["toDate"]."\"' ,JSON_ARRAY()) WHERE `Google API Call Key` = ?;";
        $db->prepare($DateSqlUpdate)->execute([$apiCallId]);


        foreach ($apiInclude->device as $deviceCode => $device) {
            //echo "starting getReport for $pa $device".$dateRange["fromDate"].":".$dateRange["toDate"]." \n";
            $response   = getReport($analytics, $apiInclude->viewId, $pa, $dateRange, $device);
            $rows       = $response[0]->getData()->getRows();
            $gaRowCount = $response[0]->getData()->getRowCount();
            $gaToken    = $response[0]->getNextPageToken();
            //echo "ending getReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";

            //echo "starting dbReportInsert for $pa $device".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";
            dbReportInsert($rows, $range, $device, $apiCallId);
            //echo "ending dbReportInsert for $pa $device".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";

            $reportSqlUpdate = "UPDATE kbase.`Google API Call Dimension` SET `Google API Call Details` = JSON_INSERT(`Google API Call Details` ,'$.\"".$apiInclude->daily["fromDate"].":".$apiInclude->daily["toDate"]."\"[99999]' ,JSON_OBJECT('ga$k$deviceCode',JSON_OBJECT('rows',?,'token',?))) WHERE `Google API Call Key` = ?";
            $db->prepare($reportSqlUpdate)->execute([$gaRowCount,$gaToken,$apiCallId]);
        }

        //echo "starting getSearchReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";
        $responseSearch = getSearchReport($webmastersService, $pa, $dateRange);
        $scRowCount     = count($responseSearch->rows);
        //echo "ending getSearchReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";

        //echo "starting getQueryReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";
        $responseQuery  = getQueryReport($webmastersService, $pa, $dateRange);
        $sqRowCount     = count($responseQuery->rows);
        //echo "ending getQueryReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";

        //echo "starting dbReportInsert for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";
        dbSearchInsert($responseSearch, $responseQuery, $range, $apiCallId);
        //echo "ending dbReportInsert for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";

        $CountSqlUpdate = "UPDATE kbase.`Google API Call Dimension` SET `Google API Call Details` = JSON_INSERT(`Google API Call Details` ,'$.\"".$dateRange["fromDate"].":".$dateRange["toDate"]."\"[99999]' ,JSON_OBJECT('sc$k',JSON_OBJECT('rows',?,'token',''),'sq$k',JSON_OBJECT('rows',?,'token',''))) WHERE `Google API Call Key` = ?;";
        $db->prepare($CountSqlUpdate)->execute([$scRowCount,$sqRowCount,$apiCallId]);
    }

    //echo "starting Daily getReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";
    $responseDaily   = getReport($analytics, $apiInclude->viewId, $pa, $apiInclude->daily,"daily");
    $rowsDaily       = $responseDaily[0]->getData()->getRows();
    $gaDailyRowCount = $responseDaily[0]->getData()->getRowCount();
    $gaDailyToken    = $responseDaily[0]->getNextPageToken();
    //echo "ending Daily getReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";

    //echo "starting Daily getSearchReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";
    $responseDailySearch = getSearchReport($webmastersService, $pa, $apiInclude->daily);
    $scDailyRowCount     = count($responseDailySearch->rows);
    //echo "starting Daily getSearchReport for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";

    //echo "starting dbDailyInsert for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";
    dbDailyInsert($rowsDaily, $responseDailySearch, $apiCallId, $apiInclude);
    //echo "ending dbDailyInsert for $pa ".$dateRange["fromDate"].":".$dateRange["toDate"]."\n";

    $executionSqlUpdate = "UPDATE kbase.`Google API Call Dimension` SET `Google API Call Details` = JSON_INSERT(`Google API Call Details` ,'$.\"".$apiInclude->daily["fromDate"].":".$apiInclude->daily["toDate"]."\"[99999]' ,JSON_OBJECT('gd$k',JSON_OBJECT('rows',?,'token',?),'sd$k',JSON_OBJECT('rows',?,'token',''))) WHERE `Google API Call Key` = ?";
    $db->prepare($executionSqlUpdate)->execute([$gaDailyRowCount,$gaDailyToken,$scDailyRowCount,$apiCallId]);

}

$executionSqlEnd = "UPDATE kbase.`Google API Call Dimension` SET `Google API Call End Date` = '".date("Y-m-d  H:i:s", time())."' WHERE `Google API Call Key` = ?;";
$db->prepare($executionSqlEnd)->execute([$apiCallId]);

//echo "End Time :".date("Y-m-d  H:i:s", time())."\n";

