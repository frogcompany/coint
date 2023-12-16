<?php
namespace App\Http\Controllers;

//use XRPL_PHP\Core\Networks;
use XRPL_PHP\Client\JsonRpcClient;
use XRPL_PHP\Wallet\Wallet;
use XRPL_PHP\Models\Account\AccountObjectsRequest;
use XRPL_PHP\Models\Transaction\TransactionTypes\AccountSet;

use function XRPL_PHP\Sugar\fundWallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\Request;


class XrplController extends Controller
{

   /**
    * アカウントの作成
    *
    */
    public function create()
    {
//	$testnetUrl = Networks::getNetwork('testnet')['jsonRpcUrl'];
	$testnetUrl = "https://s.altnet.rippletest.net:51234";
	$client = new JsonRpcClient($testnetUrl);    

	$wallet = Wallet::generate();
//	print_r($wallet);

        fundWallet($client, $wallet);

        print_r(PHP_EOL);
        print_r('Wallet address: ' . $wallet->getAddress() . PHP_EOL);
        print_r('Wallet seed: ' . $wallet->getSeed(). PHP_EOL);

	print_r('PublicKey: ' . $wallet->getPublicKey() . PHP_EOL);
        print_r('PrivateKey: ' . $wallet->getPrivateKey() . PHP_EOL);
        print_r('ClassicAddress: ' . $wallet->getClassicAddress() . PHP_EOL);

    }


    /**
     * アカウントの情報取得
     */
    public function account_info($account="")
    {
//      $testnetUrl = Networks::getNetwork('testnet')['jsonRpcUrl'];
        $testnetUrl = "https://s.altnet.rippletest.net:51234";
        $client = new JsonRpcClient($testnetUrl);

	if($account==""){
	    $account = 'rpN8yGzgACdpqwAvDd2sd5vFsA9DYzGM8A';
	}

	$request = new AccountObjectsRequest(
	    account: $account,
	    ledgerIndex: 'validated',
	    deletionBlockersOnly: true
	);

	$response = $client->syncRequest($request);
//	$json = json_decode($response->getBody());
//	print_r($json);
        print_r($response);

    }


    /**
     * 新しいウォレットのウォレット設定と独自トークンの配布をする
     * Allow Trust Line Clawback設定を有効にする必要があるのでセット
     * 1.AccountSetでフラグ設定したウォレットを設定
     * 2.TrustLineセット
     * 3.トークンをセット
     * 4. 
     */

    public function account_set($account="")
    {
//	$testnetUrl = Networks::getNetwork('testnet')['jsonRpcUrl'];
        $testnetUrl = "https://s.altnet.rippletest.net:51234";
	$client = new JsonRpcClient($testnetUrl);

        if($account==""){
            $account = 'rpN8yGzgACdpqwAvDd2sd5vFsA9DYzGM8A';
        }

	$flag = 8;// 対象のフラグ

	$tx = [
	    "TransactionType" => "AccountSet",
	    "Account" => $account,
	    "SetFlag" => $flag
	];

	$bankWallet = $client->fundWallet();

	$walletConfigTxPrepared = $client->autofill($tx);
	$walletConfigTxSigned = $bankWallet->sign($walletConfigTxPrepared);
	$accountSetResponse = $client->submitAndWait($walletConfigTxSigned['tx_blob']);

        $request = new AccountObjectsRequest(
	    transactionType: 'accountSet',
	    account: $account,
            setFlag: $flag
        );

        $response = $client->syncRequest($request);
         print_r($response);


	// TrustSet
	$trustSetTx = [
	    "TransactionType" => "TrustSet",
	    "Account" => $merchantWallet->getAddress(),
	    "LimitAmount" => [
	        "currency" => $tokenName,
	        "issuer" => $bankWallet->getAddress(),
	        "value" => $issuerBalance
	    ]
	];

	$trustSetPreparedTx = $client->autofill($trustSetTx);
	$signedTx = $merchantWallet->sign($trustSetPreparedTx);
	$trustSetResponse = $client->submitAndWait($signedTx['tx_blob']);

	$customerWallet = $client->fundWallet();


	// Creating trust line from customer wallet to bank wallet,
	//
	$trustSetTx = [
	    "TransactionType" => "TrustSet",
	    "Account" => $customerWallet->getAddress(),
	    "LimitAmount" => [
	        "currency" => $tokenName,
	        "issuer" => $bankWallet->getAddress(),
	        "value" => $issuerBalance
	    ]
	];

	$trustSetPreparedTx = $client->autofill($trustSetTx);
	$signedTx = $customerWallet->sign($trustSetPreparedTx);
	$trustSetResponse = $client->submitAndWait($signedTx['tx_blob']);
	
	// print_r($trustSetResponse->getResult()['hash']}" . PHP_EOL);


	// print_r("Sending {$customerBalance} Tokens from issuer wallet to customer wallet" . PHP_EOL);
	$sendTokenTx = [
	    "TransactionType" => "Payment",
	    "Account" => $bankWallet->getAddress(),
	    "Amount" => [
	            "currency" => $tokenName,
	            "value" => $customerBalance,
	            "issuer" => $bankWallet->getAddress()
	    ],
	    "Destination" => $customerWallet->getAddress()
	];
	$preparedPaymentTx = $client->autofill($sendTokenTx);
	$signedPaymentTx = $bankWallet->sign($preparedPaymentTx);
	$paymentResponse = $client->submitAndWait($signedPaymentTx['tx_blob']);
	// "Token payment  TxHash: {$paymentResponse->getResult()['hash']}" . PHP_EOL . PHP_EOL);

	$examplePaymentAmount = '15';
	//  "Sending {$examplePaymentAmount} Tokens from customer wallet to merchant wallet, please wait..." . PHP_EOL);
	$sendTokenTx = [
	    "TransactionType" => "Payment",
	    "Account" => $customerWallet->getAddress(),
	    "Amount" => [
	        "currency" => $tokenName,
	        "value" => $examplePaymentAmount,
	        "issuer" => $bankWallet->getAddress()
	    ],
	    "Destination" => $merchantWallet->getAddress()
	];
	$preparedPaymentTx = $client->autofill($sendTokenTx);
	$signedPaymentTx = $customerWallet->sign($preparedPaymentTx);
	$paymentResponse = $client->submitAndWait($signedPaymentTx['tx_blob']);
	// print_r("Token payment done! TxHash: " . Color::WHITE . "{$paymentResponse->getResult()['hash']}" . PHP_EOL . PHP_EOL);

	// print_r("You can check wallets/accounts and transactions m"  . PHP_EOL . PHP_EOL);



    }


    /**
     * Clawback
     */
    public function clawback($account="")
    {
//      $testnetUrl = Networks::getNetwork('testnet')['jsonRpcUrl'];
        $testnetUrl = "https://s.altnet.rippletest.net:51234";
        $client = new JsonRpcClient($testnetUrl);

        if($account==""){
            $account = 'rpN8yGzgACdpqwAvDd2sd5vFsA9DYzGM8A';
        }

	$currency = "CTY"; // currency
	$issuer = "rLiwjXvZbSfKzcKwNTYQWK6rzUgQBkyX5A"; // トークン所有者のアカウント
	$value = "20";// 回収分

	$amount = [
	      "currency" => $currency,
	      "issuer" => $issuer,
	      "value" => $value
	];
	

        $request = [
            "TransactionType" => "Clawback",
            "Account" => $account,
            "Amount" => $amount
        ];


        $response = $client->syncRequest($request);
	 print_r($response);
    }










}


