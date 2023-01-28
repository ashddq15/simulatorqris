<?php
	$clientId = 'xxxxx';
        $sharedkey = 'xxxxx';
        date_default_timezone_set('UTC');
        $systrace =  'INV-' . time();
        $secretKey = 'xxxxx';
        $abc = $clientId . $sharedkey . $systrace;
        $words = hash_hmac('sha1', $abc, $secretKey, false);
        $urlsignon = 'https://staging.doku.com/dokupay/h2h/signon?clientId=' . $clientId . '&clientSecret=' . $secretKey . '&systrace=' . $systrace . '&words=' . $words . '&version=1.0&responseType=1';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlsignon,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $responsesignon = curl_exec($curl);
        curl_close($curl);
        $hasilsignon = json_decode($responsesignon, true);
        $accesstoken = $hasilsignon['accessToken'];
        //Check Balance API
        $accountid = 'xxxxx';
        $abcblc = $clientId . $systrace . $sharedkey . $accountid; //(clientId + systraceSignOn +sharedKey + accountId
        $wordsbalance = hash_hmac('sha1', $abcblc, $secretKey, false);
        $urlbalance = 'https://staging.doku.com/dokupay/h2h/custsourceoffunds?clientId=' . $clientId . '&accessToken=' . $accesstoken . '&accountId=' . $accountid . '&words=' . $wordsbalance . '&version=1.0';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlbalance,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $responsebalance = curl_exec($curl);
        curl_close($curl);
        $hasilbalance = json_decode($responsebalance, true);
        $balance = $hasilbalance['lastBalance'];
        $qrcodevalue = rawurlencode($_GET['qr']);//rawurlencode($this->request->getVar('qr'));
        //var_dump($qrcodevalue);
        $qrdecode = $_GET['qr'];
        date_default_timezone_set('UTC');
        $timestamp      = date('Y-m-d\TH:i:s\Z');
        $abc = $clientId . $accountid . $sharedkey . $qrdecode; //((clientId + accountId + sharedKey +qrCodeValue)
        $wordsinquiry = hash_hmac('sha1', $abc, $secretKey, false);
        $urlinquiry = 'https://staging.doku.com/dokupay/h2h/doInquiryQris?clientId=' . $clientId . '&accessToken=' . $accesstoken . '&accountId=' . $accountid . '&qrCodeValue=' . $qrcodevalue . '&words=' . $wordsinquiry . '&version=3.0&responseType=1';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlinquiry,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $responseinquiry = curl_exec($curl);
        curl_close($curl);
        $hasilinquiry = json_decode($responseinquiry, true);
        //$transactionId = $hasilinquiry['transactionId'];
        if (!empty($hasilinquiry['transactionId'])) {
        $transactionId = $hasilinquiry['transactionId'];
        $merchantname = $hasilinquiry['merchantName'];
        $merchantcity = $hasilinquiry['merchantCity'];
        $amount = $hasilinquiry['amount'];
        $fee = $hasilinquiry['conveniencesFee'];
         } else {
          $amount = 0;
          $merchantname = $hasilinquiry['merchantName'];
          $merchantcity = $hasilinquiry['merchantCity'];
          $transactionId = 'INV-'.time();
          $fee = 0;
        };
        function uang($angka)
        {
            $angka = number_format($angka);
            $angka = str_replace(',', '.', $angka);
            $angka = "$angka";
            return $angka;
        }
        $finalamount = uang($amount);
        $finalbalance = uang($balance);
            $mname = $merchantname;
            //$transactionId = $transactionId;
            $mname = $merchantname;
            $mcity = $merchantcity;
            $amount = $finalamount;
            $amount2 = $amount;
            $qr = $qrcodevalue;
            $accessToken = $accesstoken;
            $systrace = $systrace;
            $balance = $finalbalance;
            $finalfee = uang($fee);


 ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="container mt-4">
    <div class="row mb-2">
        <div class="col text-center">
            <h1>Simulator QRIS</h1>
            <div class="col">
                <h5>Balance : Rp <?= $balance; ?></h5>
                <div class="col mt-5">
                    <img src="/Simulator-QRIS/001-seller.png" style="width: 100px;height: 100px;" alt="">
                </div>
                <div class="col mt-4">
                    <h4><?= $mname; ?></h4>
                    <p><?= $mcity; ?></p>
                    <?php if ($amount == 0) { ?>
                    <label for="amount2">Amount :</label>
                    <input type="number" id="amount2" name="amount2"><br><br>
                  <?php } else { ?>
                    <h4>Amount :  Rp <?= $amount; ?></h4>
                    <h5>fee :  Rp <?= $finalfee; ?></h5>
                  <?php } ?>
                </div>
                <div class="col mt-4">
                </div>
                <button onclick="payment()" class="btn btn-primary">Pay</button>
            </div>
        </div>
    </div>
</div>

<script>
    function payment() {
      var amount = '<?= $amount; ?>';
      if (amount == 0){
        var qrcode = '<?= $qr; ?>';
        var accessToken = '<?= $accessToken; ?>';
        var finalamount = document.getElementById("amount2").value;
        var transactionId = '<?= $transactionId; ?>';
        var merchant = '<?= $mname; ?>';
        var fee = '<?= $finalfee; ?>';
        window.location.href = "/Simulator-QRIS/payment.php?qr=" + qrcode + "&token=" + accessToken + "&trx=" + transactionId + "&amount=" + finalamount + "&merchant=" + merchant + "&fee=" + fee;
        } else {
          var finalamount = '<?= $amount2; ?>';
          var transactionId = '<?= $transactionId; ?>';
          var qrcode = '<?= $qr; ?>';
          var accessToken = '<?= $accessToken; ?>';
          var merchant = '<?= $mname; ?>';
          var fee = '<?= $fee; ?>';
          window.location.href = "/Simulator-QRIS/payment.php?qr=" + qrcode + "&token=" + accessToken + "&trx=" + transactionId + "&amount=" + finalamount + "&merchant=" + merchant + "&fee=" + fee;
        };

    };
</script>
  </body>
</html>
