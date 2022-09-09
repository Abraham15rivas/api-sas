<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
	/**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
	protected function success($data, string $message = null, int $code = 200) {
		return response()->json([
			'success' => true,
			'message' => $message,
			'data' => $data
		], $code);
	}

	/**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
	protected function error(string $message = null, int $code, $data = null) {
		return response()->json([
			'success' => false,
			'message' => $message,
			'data' => $data
		], $code);
	}

	public function encrypt($string, $key) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
		   $char = substr($string, $i, 1);
		   $keychar = substr($key, ($i % strlen($key))-1, 1);
		   $char = chr(ord($char)+ord($keychar));
		   $result.=$char;
		}
		return base64_encode($result);
	}

	public function decrypt($string, $key) {
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
		   $char = substr($string, $i, 1);
		   $keychar = substr($key, ($i % strlen($key))-1, 1);
		   $char = chr(ord($char)-ord($keychar));
		   $result.=$char;
		}
		return $result;
	}

	public function reportError($error) {
        Log::info('====================== ERROR ======================');
        Log::info('Date America/Caracas: ' . Carbon::now()->setTimezone('America/Caracas')->format('Y-m-d H:i:s'));
        Log::info('File: ' . $error->getFile());
        Log::info('Message: ' . $error->getMessage());
        Log::info('Line: ' . $error->getLine());
        Log::info('===================================================');
    }
}