<?php
/**
 * @author: Yudu <uicosp@gmail.com>
 * @date: 2017/1/10
 */

namespace Uicosp\ServiceSignature;

use Closure;
use Validator;

class VerifySignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($input = $request->input(), [
            'service_key' => 'required',
            'timestamp' => 'required',
            'nonce' => 'required',
            'signature' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $arr = array_except($input, ['signature']);
        $arr = array_merge($arr, ['service_secret' => config("service-signature.clients.{$input['service_key']}")]);
        ksort($arr, SORT_STRING);
        $sign = md5(json_encode($arr));

        if ($sign != $input['signature']) {
            return response()->json('invalid signature', 400);
        }

        if (time() - $input['timestamp'] > config("service-signature.expires_in")) {
            return response()->json('expired', 401);
        }

        return $next($request);
    }
}
