<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php

    require __DIR__ . '/vendor/autoload.php';

    $client = new \Google_Client();
    $client->setApplicationName('Google Sheets PHP');
    $client->setScopes(array(\Google_Service_Sheets::SPREADSHEETS));
    $client->setAccessType('offline');
    $client->setAuthConfig(__DIR__ . '/credentials.json');
    $service = new Google_Service_Sheets($client);
    $spreadsheetId = "1nx5eS4LnlLKGUjwxJBk8Ul7MpELh4JBZVjvYIpm03x4";

    $range = "test!A1:B4";
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    if(empty($values)) {
      echo "no data found";
    } else {
      foreach ($values as $row) {
        echo 1;
        echo sprintf($row[0], $row[1], $row[2]);
      }
    }
  ?>
</body>
</html>




// putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/client_secret.json');
// $client = new Google_Client;
// $client->useApplicationDefaultCredentials();

// $client->setApplicationName("Something to do with my representatives");
// $client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);

// if ($client->isAccessTokenExpired()) {
//     $client->refreshTokenWithAssertion();
// }

// $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
// ServiceRequestFactory::setInstance(
//     new DefaultServiceRequest($accessToken)
// );