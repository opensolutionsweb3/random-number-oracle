# Random number oracle by Open Solutions

Random number oracle - it is an Oracle for generating deterministic digital signatures for random numbers generation on the Waves blockchain. It includes 2 functions: [sign](https://github.com/opensolutionsweb3/random-number-oracle/blob/046d1db98d73685e54668095f42650ddb81a7b70/GameController.php#L11 ) and [sendtoBlockchain](https://github.com/opensolutionsweb3/random-number-oracle/blob/046d1db98d73685e54668095f42650ddb81a7b70/GameController.php#L56)

## ```Sign($data)```

### Description
Signs incoming data with three RSA private keys and returns an array containing message and 3 RSA signatures.

### Parameters
```$rsa->setHash('sha256');``` - to use a different hashing method, replace `sha256` with your own

### Require
* ```(string) $data ``` - message for sign (tx.id)

### Return
* ```array``` : array includes message and 3 RSA signs
> `message` returns as string, `sign` returns as base64 encoded string

## ```sendtoBlockchain($data)```
Creates Invoke transaction with arguments and broadcast it to the blockchain

### Parameters
- ```$wk   = new WavesKit('T');``` - `T` for Testnet, `W` for Mainnet
- ```$seed = 'your seed here';``` - change `your seed here` on your own seed phrase
- ```$dApp = 'your dApp address';``` - change `your dApp address` on your dApp address
- ```tx = $wk->txInvokeScript($dApp, 'dApp method', $args, $payments);``` - change `dApp method` on your @Callable function name

### Require
 ```(array) $data``` - result of Sign function

### Return
```tx.id|error```
> `tx.id` if transaction was broadcasted, `error` on failure

# Used libraries
* [WavesKit](https://github.com/deemru/WavesKit) : WavesKit documentation
* [phpseclib/phpseclib](https://packagist.org/packages/phpseclib/phpseclib)
* [laravel](https://laravel.com/)
 
