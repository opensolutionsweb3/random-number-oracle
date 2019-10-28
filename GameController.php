<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use \phpseclib\Crypt\RSA;
use \deemru\WavesKit;

class GameController extends Controller
{
    public function Sign($data)
    {
        $privateKeys[0] = Storage::disk('local')->get('key1');
        $privateKeys[1] = Storage::disk('local')->get('key2');
        $privateKeys[2] = Storage::disk('local')->get('key3');

        $rsa = new RSA();
        foreach ($privateKeys as $index => $privateKey) {
            $rsa->loadKey($privateKey);
            $rsa->setHash('sha256');
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);
            $sign[$index] = base64_encode($rsa->sign($data));
        }


        $answer = [
            [
                'data' => $data,
                'sign' => $sign[0],
            ],
            [
                'data' => $data,
                'sign' => $sign[1],
            ],
            [
                'data' => $data,
                'sign' => $sign[2],
            ]
        ];

        $blockchainAnswer = $this->sendtoBlockchain($answer);

        if (empty($blockchainAnswer)) {
            $frontendAnswer[] = ['status' => 'error'];
            return response()->json($frontendAnswer, 400);
        } else {

            $frontendAnswer[] = [
                'status' => 'ok',
                'id'     => $blockchainAnswer
            ];
            return json_encode($frontendAnswer, 480);
        }
    }

    private function sendtoBlockchain($data)
    {
        $wk   = new WavesKit('T');
        $seed = 'your seed here';
        $dApp = 'your dApp address';

        $args = [
            $data[0]['data'],
            $data[0]['sign'],
            $data[1]['sign'],
            $data[2]['sign'],
        ];

        $payments = [];

        $wk->setSeed($seed);
        $tx = $wk->txInvokeScript($dApp, 'dApp method', $args, $payments); // second argument of this, it's your dApp method. Please don't forget to change it.
        $tx = $wk->txSign($tx);
        $tx = $wk->txBroadcast($tx);

        return $tx['id'];
    }
}
