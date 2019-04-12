<?php

include_once __DIR__ . '/api.include.php';

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

function getReport($analytics,$view,$website,$date) {

    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate($date["fromDate"]);
    $dateRange->setEndDate($date["toDate"]);

    $pageviews = new Google_Service_AnalyticsReporting_Metric();
    $pageviews->setExpression('ga:pageviews');
    $pageviews->setAlias('Pageviews');
    $pageValue = new Google_Service_AnalyticsReporting_Metric();
    $pageValue->setExpression("ga:pageValue");
    $pageValue->setAlias("Page Value");
    $users = new Google_Service_AnalyticsReporting_Metric();
    $users->setExpression("ga:users");
    $users->setAlias("Users");
    $sessions = new Google_Service_AnalyticsReporting_Metric();
    $sessions->setExpression("ga:sessions");
    $sessions->setAlias("Sessions");

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

    $simpleSegment = new Google_Service_AnalyticsReporting_SimpleSegment();
    $simpleSegment->setOrFiltersForSegment(array($orFiltersForSegment));

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
    $request->setMetrics(array($pageviews,$pageValue,$users,$sessions));
    $request->setPageSize(10000);

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet( $body );
}

function getSearchReport($webmastersService,$website,$dateRange) {

    $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
    $query->setDimensions(array('page'));
    $query->setStartDate($dateRange["fromDate"]);
    $query->setEndDate($dateRange["toDate"]);
    $query->setRowLimit(5000);

    $report = $webmastersService->searchanalytics->query('https://'.$website.'/', $query);
    return $report;
}
function getQueryReport($webmastersService,$website,$dateRange) {

    $query = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
    $query->setDimensions(array('page','query'));
    $query->setStartDate($dateRange["fromDate"]);
    $query->setEndDate($dateRange["toDate"]);
    $query->setRowLimit(5000);

    $report = $webmastersService->searchanalytics->query('https://'.$website.'/', $query);
    return $report;
}

function dbReportInsert($rows,$responseSearch,$responseQuery,$range,$apiCallId,$parameter)
{
    $conn = new mysqli($parameter->dbServername, $parameter->dbUsername, $parameter->dbPassword, $parameter->dbName);

    $sql = "";
    foreach ($rows as $r) {
        $dimensions = $r->getDimensions();
        $metrics = $r->getMetrics();
        $values = $metrics[0]->getValues();
        $hostname = $dimensions[0];
        $pagePath = $dimensions[1];
        $path = parse_url($pagePath, PHP_URL_PATH);
        $pageviews = $values[0];
        $pageValue = $values[1];
        $users = $values[2];
        $sessions = $values[3];

        $sql .= "INSERT IGNORE INTO `Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('https://".$hostname.$pagePath."' ,'$hostname' ,'$pagePath' ,'$path');
            INSERT INTO `Google Data`(`Google API Call Key`, `Google Webpage Key`, `Google $range Page Value`, `Google $range Pageviews`, `Google $range Sessions`, `Google $range Users`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM `Google Webpage` WHERE `Google Webpage URL` = 'https://".$hostname.$pagePath."') ,'$pageValue' ,'$pageviews' ,'$sessions' ,'$users') ON DUPLICATE KEY UPDATE `Google $range Page Value` = '$pageValue', `Google $range Pageviews` = '$pageviews', `Google $range Sessions` = '$sessions', `Google $range Users` = '$users' ;";
    }
    foreach ($responseSearch as $r) {
        $hostname = parse_url($r->keys[0], PHP_URL_HOST);
        $path = parse_url($r->keys[0], PHP_URL_PATH);
        $query = parse_url($r->keys[0], PHP_URL_QUERY) !== null ? '?' . parse_url($r->keys[0], PHP_URL_QUERY) : '';
        $fragment = parse_url($r->keys[0], PHP_URL_FRAGMENT) !== null ? '#' . parse_url($r->keys[0], PHP_URL_FRAGMENT) : '';

        $sql .= "INSERT IGNORE INTO `Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('".$r->keys[0]."' ,'$hostname' ,'".$path.$query.$fragment."' ,'$path');
            INSERT INTO `Google Data`(`Google API Call Key`, `Google Webpage Key`, `Google $range SC Clicks`, `Google $range SC CTR`, `Google $range SC Impressions`, `Google $range SC Position`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM `Google Webpage` WHERE `Google Webpage URL` = '".$r->keys[0]."'),'$r->clicks' ,'$r->ctr' ,'$r->impressions' , '$r->position') ON DUPLICATE KEY UPDATE `Google $range SC Clicks` = '$r->clicks', `Google $range SC CTR` = '$r->ctr', `Google $range SC Impressions` = '$r->impressions', `Google $range SC Position` = '$r->position' ;";
    }
    foreach ($responseQuery as $q) {
        $hostname = parse_url($q->keys[0], PHP_URL_HOST);
        $path = parse_url($q->keys[0], PHP_URL_PATH);
        $query = parse_url($q->keys[0], PHP_URL_QUERY) !== null ? '?' . parse_url($q->keys[0], PHP_URL_QUERY) : '';
        $fragment = parse_url($q->keys[0], PHP_URL_FRAGMENT) !== null ? '#' . parse_url($q->keys[0], PHP_URL_FRAGMENT) : '';

        $sql .= "INSERT IGNORE INTO `Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('".$q->keys[0]."' ,'$hostname' ,'".$path.$query.$fragment."' ,'$path');
            INSERT INTO `Google Query Data` (`Google API Call Key`, `Google Webpage Key`, `Google Query`, `Google $range Clicks`, `Google $range CTR`, `Google $range Impressions`, `Google $range Position`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM `Google Webpage` WHERE `Google Webpage URL` = '".$q->keys[0]."'),'".$q->keys[1]."','$q->clicks' ,'$q->ctr' ,'$q->impressions' , '$q->position') ON DUPLICATE KEY UPDATE `Google $range Clicks` = '$q->clicks', `Google $range CTR` = '$q->ctr', `Google $range Impressions` = '$q->impressions', `Google $range Position` = '$q->position';";
    }
    if ($conn->multi_query($sql) === TRUE) {
        echo "Google Analytics and Search Console data updated successfully" . "\n";
    } else {
        echo "Error: " . "\n" . $sql . "\n" . $conn->error . "\n";
    }
    $conn->close();
}
function dbDailyInsert($rowsDaily,$responseDailySearch,$apiCallId,$parameter)
{
    $dbconn = new mysqli($parameter->dbServername, $parameter->dbUsername, $parameter->dbPassword, $parameter->dbName);

    $sql = "";

    foreach ($rowsDaily as $rd) {
        $dimensions = $rd->getDimensions();
        $metrics = $rd->getMetrics();
        $values = $metrics[0]->getValues();
        $hostname = $dimensions[0];
        $pagePath = $dimensions[1];
        $path = parse_url($pagePath, PHP_URL_PATH);
        $pageviews = $values[0];
        $pageValue = $values[1];
        $users = $values[2];
        $sessions = $values[3];

        $sql .= "INSERT IGNORE INTO `Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('https://".$hostname.$pagePath."' ,'$hostname' ,'$pagePath' ,'$path');
            INSERT INTO `Google Time Series`(`Google API Call Key`, `Google Webpage Key`, `Google Time Series Date`, `Google Time Series Page Value`, `Google Time Series Pageviews`, `Google Time Series Sessions`, `Google Time Series Users`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM `Google Webpage` WHERE `Google Webpage URL` = 'https://".$hostname.$pagePath."') ,'".$parameter->daily["fromDate"]."' ,'$pageValue' ,'$pageviews' ,'$sessions' ,'$users') ON DUPLICATE KEY UPDATE `Google Time Series Page Value` = '$pageValue', `Google Time Series Pageviews` = '$pageviews', `Google Time Series Sessions` = '$sessions', `Google Time Series Users` = '$users' ;";
    }
    foreach ($responseDailySearch as $rq) {
        $hostname = parse_url($rq->keys[0], PHP_URL_HOST);
        $path = parse_url($rq->keys[0], PHP_URL_PATH);
        $query = parse_url($rq->keys[0], PHP_URL_QUERY) !== null ? '?' . parse_url($rq->keys[0], PHP_URL_QUERY) : '';
        $fragment = parse_url($rq->keys[0], PHP_URL_FRAGMENT) !== null ? '#' . parse_url($rq->keys[0], PHP_URL_FRAGMENT) : '';

        $sql .= "INSERT IGNORE INTO `Google Webpage`(`Google Webpage URL`, `Google Webpage Website`, `Google Webpage Original Path`, `Google Webpage Canonical Path`) VALUES ('".$rq->keys[0]."' ,'$hostname' ,'".$path.$query.$fragment."' ,'$path');
            INSERT INTO `Google Time Series`(`Google API Call Key`, `Google Webpage Key`, `Google Time Series Date`, `Google Time Series Clicks`, `Google Time Series CTR`, `Google Time Series Impressions`, `Google Time Series Position`) VALUES ('$apiCallId',(SELECT `Google Webpage Key` FROM `Google Webpage` WHERE `Google Webpage URL` = '".$rq->keys[0]."') ,'".$parameter->daily["fromDate"]."' ,'$rq->clicks' ,'$rq->ctr' ,'$rq->impressions' , '$rq->position') ON DUPLICATE KEY UPDATE `Google Time Series Clicks` = '$rq->clicks', `Google Time Series CTR` = '$rq->ctr', `Google Time Series Impressions` = '$rq->impressions', `Google Time Series Position` = '$rq->position' ;";
    }
    if ($dbconn->multi_query($sql) === TRUE) {
        echo "Google Analytics and Search Console Daily data updated successfully" . "\n";
    } else {
        echo "Error: " . "\n" . $sql . "\n" . $dbconn->error . "\n";
    }
    $dbconn->close();
}

$link = new mysqli($apiInclude->dbServername, $apiInclude->dbUsername, $apiInclude->dbPassword, $apiInclude->dbName);

$executionSqlStart = "INSERT INTO `Google API Call Dimension` (`Google API Call Start Date`, `Google API Call ID`) VALUES ('".date("Y-m-d  H:i:s",time())."','{}');";
mysqli_query($link, $executionSqlStart);
$apiCallId = mysqli_insert_id($link);
echo "Start Time :".date("Y-m-d  H:i:s",time()). "\n";
echo "API Call ID :".$apiCallId. "\n";
foreach ($apiInclude->property as $k => $pa){
    $DateDailySqlUpdate = "UPDATE google.`Google API Call Dimension` SET `Google API Call ID` = JSON_INSERT(`Google API Call ID` ,'$.\"".$apiInclude->daily["fromDate"].":".$apiInclude->daily["toDate"]."\"' ,JSON_ARRAY()) WHERE `Google API Call Key` = '$apiCallId';";
    mysqli_query($link, $DateDailySqlUpdate);

    $analytics = initializeAnalytics($apiInclude->key);
    $webmastersService = initializeSearch($apiInclude->key);
    foreach ($apiInclude->dateRange as $range =>$dateRange){

        $DateSqlUpdate = "UPDATE google.`Google API Call Dimension` SET `Google API Call ID` = JSON_INSERT(`Google API Call ID` ,'$.\"".$dateRange["fromDate"].":".$dateRange["toDate"]."\"' ,JSON_ARRAY()) WHERE `Google API Call Key` = '$apiCallId';";
        mysqli_query($link, $DateSqlUpdate);

        $response = getReport($analytics,$apiInclude->viewId,$pa,$dateRange);
        $rows = $response[0]->getData()->getRows();
        $gaRowCount = $response[0]->getData()->getRowCount();
        $gaToken = $response[0]->getNextPageToken();

        $responseSearch = getSearchReport($webmastersService,$pa,$dateRange);
        $scRowCount = count($responseSearch->rows);
        $responseQuery = getQueryReport($webmastersService,$pa,$dateRange);
        $sqRowCount = count($responseQuery->rows);

        dbReportInsert($rows,$responseSearch,$responseQuery,$range,$apiCallId,$apiInclude);

        $CountSqlUpdate = "UPDATE google.`Google API Call Dimension` SET `Google API Call ID` = JSON_INSERT(`Google API Call ID` ,'$.\"".$dateRange["fromDate"].":".$dateRange["toDate"]."\"[9999]' ,JSON_OBJECT('ga$k',JSON_OBJECT('rows','$gaRowCount','token','$gaToken'),'sc$k',JSON_OBJECT('rows','$scRowCount'),'sq$k',JSON_OBJECT('rows','$sqRowCount'))) WHERE `Google API Call Key` = '$apiCallId';";
        mysqli_query($link, $CountSqlUpdate);
    }
    $responseDaily = getReport($analytics,$apiInclude->viewId,$pa,$apiInclude->daily);
    $rowsDaily = $responseDaily[0]->getData()->getRows();
    $gaDailyRowCount = $responseDaily[0]->getData()->getRowCount();
    $gaDailyToken = $responseDaily[0]->getNextPageToken();

    $responseDailySearch = getSearchReport($webmastersService,$pa,$apiInclude->daily);
    $scDailyRowCount = count($responseDailySearch->rows);

    dbDailyInsert($rowsDaily,$responseDailySearch,$apiCallId,$apiInclude);

    $executionSqlUpdate = "UPDATE google.`Google API Call Dimension` SET `Google API Call ID` = JSON_INSERT(`Google API Call ID` ,'$.\"".$apiInclude->daily["fromDate"].":".$apiInclude->daily["toDate"]."\"[9999]' ,JSON_OBJECT('gd$k',JSON_OBJECT('rows','$gaDailyRowCount','token','$gaDailyToken'),'sd$k',JSON_OBJECT('rows','$scDailyRowCount'))) WHERE `Google API Call Key` = '$apiCallId';";
    mysqli_query($link, $executionSqlUpdate);
}
$executionSqlEnd = "UPDATE `Google API Call Dimension` SET `Google API Call End Date` = '".date("Y-m-d  H:i:s",time())."' WHERE `Google API Call Key` = '$apiCallId';";
mysqli_query($link, $executionSqlEnd);
echo "End Time :".date("Y-m-d  H:i:s",time()). "\n";

$link->close();