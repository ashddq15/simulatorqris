<?php

	$qr = $_GET['qr'];
	$fee = $_GET['fee'];
        $token = $_GET['token'];
        $qrencode = rawurlencode($_GET['qr']);
        $trx = $_GET['trx'];
        $amount = $_GET['amount'];
        $merchant = $_GET['merchant'];
        $clientId = 'xxxx';
        $sharedkey = 'xxxx';
        $acccountId = 'xxxxx';
        $secretKey = 'xxxxx';
        $abc = $clientId . $acccountId . $sharedkey . $token . $qr . $trx; //(clientId + accountId + sharedKey + accessToken + qrCodeValue + invoice
        $words = hash_hmac('sha1', $abc, $secretKey, false);
        $urlpayment = 'https://staging.doku.com/dokupay/h2h/doPaymentQris?clientId=' . $clientId . '&accessToken=' . $token . '&accountId=' . $acccountId . '&qrCodeValue=' . $qrencode . '&words=' . $words . '&invoice=' . $trx . '&amount=' . $amount . '&conveniencesFee' . $fee .'&version=3.0';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlpayment,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $responsepayment = curl_exec($curl);
        curl_close($curl);
        $hasilpayment = json_decode($responsepayment, true);

        $rc = $hasilpayment['responseCode'];
        $rm = $hasilpayment['responseMessage']['en'];
        if ($rc == '0000') {
            $rfnum = $hasilpayment['referenceNumber'];
            $aCode = $hasilpayment['approvalCode'];
                $status = $rm;
                $merchant = $merchant;
                $acquirer = 'DOKU';
                $amount = $amount;
                $trx = $trx;
                $rf = $rfnum;
                $rc = $rc;
                $acode = $aCode;
        } else {
        		$status = $rm;
                $merchant = $merchant;
                $acquirer = 'DOKU';
                $amount = $amount;
                $trx = $trx;
                $rc = $rc;
                $statush1 = 'FAILED';
        };

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
  	<?php if ($rc == '0000') { ?>
  	<div class="container mt-4">
    <div class="row mb-2">
        <div class="col text-center">
            <h1>Simulator QRIS</h1>
            <div class="col">
                <div class="col mt-5">
                    <img src="/Simulator-QRIS/icon_success.png" style="width: 100px;height: 100px;" alt="">
                </div>
                <div class="col mt-4">
                    <h3><?= $status; ?></h3>
                    <tr>
                        <h5>Transaction at <?= $merchant; ?></h5>
                        <td class="align-baseline">Amount : </td>
                        <td class="align-text-top">Rp <?= $amount; ?></td><br>
                        <td class="align-baseline">Fee : </td>
                        <td class="align-text-top">Rp <?= $fee; ?></td><br>
                        <td class="align-baseline">Acquirer : </td>
                        <td class="align-text-top"><?= $acquirer; ?></td><br>
                        <td class="align-baseline">Transaction ID : </td>
                        <td class="align-text-top"><?= $trx; ?></td><br>
                        <td class="align-baseline">Reference Number : </td>
                        <td class="align-text-top"><?= $rf; ?></td><br>
                        <td class="align-baseline">Approval Code : </td>
                        <td class="align-text-top"><?= $acode; ?></td>
                    </tr>
                </div>
                <div class="col mt-4">
                    <a href="/Simulator-QRIS" class="btn btn-primary">Try Again?</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
	<div class="container mt-4">
    <div class="row mb-2">
        <div class="col text-center">
            <h1>Simulator QRIS</h1>
            <div class="col">
                <div class="col mt-5">
                    <img src="/Simulator-QRIS/icon_failed.png" style="width: 100px;height: 100px;" alt="">
                </div>
                <div class="col mt-4">
                    <h3> <?= $statush1; ?></h3>
                    <h4><?= $status; ?></h4>
                </div>
                <div class="col mt-4">
                    <a href="/Simulator-QRIS" class="btn btn-primary">Try Again?</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }; ?>
  </body>
</html>

