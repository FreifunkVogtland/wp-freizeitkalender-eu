<?php
$messageList = [];
if ($dom->getElementsByTagName('out')->length === 0) {
    $messageList[] = 'An error occured';
    $messageList[] = 'Empty Request.';
}
$domElementError = $dom->getElementsByTagName('error')->item(0);
if ($domElementError instanceof DOMElement) {
    $messageList[] = 'An error occured';
    $domElementMessage = $domElementError->getElementsByTagName('message')->item(0);
    if ($domElementMessage instanceof DOMElement) {
        $messageList[] = $domElementMessage->nodeValue;
        if (end($messageList) === 'Zugriff für Filter nicht erlaubt') {
            $messageList[] = 'Passwort benötigt';
        }
    }
}

$domElementFilter = $dom->getElementsByTagName('filter')->item(0);
$filterName = 'No name returned';
if ($domElementFilter instanceof DOMElement) {
    $filterName = $domElementFilter->getElementsByTagName('name')->item(0)->nodeValue;
}
$messageList[] = 'FilterName: "' . $filterName . '"';
$domElementWhere = $dom->getElementsByTagName('where')->item(0);
$eventCount = 0;
if ($domElementWhere instanceof DOMElement) {
    $eventCount = (int) $domElementWhere->getElementsByTagName('numberofevents')->item(0)->nodeValue;
}
$messageList[] = 'EventCount: "' . $eventCount . '"';