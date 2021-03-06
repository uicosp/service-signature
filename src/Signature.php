<?php
/**
 * @author: Yudu <uicosp@gmail.com>
 * @date: 2017/1/10
 */

namespace Uicosp\ServiceSignature;

class Signature
{
    /**
     * 生成签名，并以数组形式返回
     * @param $service
     * @param array $query
     * @return array
     */
    public static function genArray($service, array $query = [])
    {
        $service = strtolower($service);
        // 添加基础参数到 query
        $query = array_merge($query, [
            'service_key' => config("service-signature.{$service}.service_key"),
            'timestamp' => time(),
            'nonce' => str_random(10),
        ]);

        // 计算签名
        $arr = array_merge($query, ['service_secret' => config("service-signature.{$service}.service_secret")]);
        ksort($arr, SORT_STRING);
        $sign = md5(json_encode($arr, JSON_NUMERIC_CHECK));

        // 添加签名到 query
        $query = array_merge($query, ['signature' => $sign]);

        return $query;
    }

    /**
     * 生成签名，并用 http_build_query 以字符串形式返回
     * @param $service
     * @param array $query
     * @return string
     */
    public static function genString($service, array $query = [])
    {
        $query = self::genArray($service, $query);

        return http_build_query($query);
    }
}