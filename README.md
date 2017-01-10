# 服务签名 for Laravel 5.3

## 安装

`composer require uicosp/service-signature`

然后在 `config/app.php` 的 `providers` 数组中添加 

```
Uicosp\ServiceSignature\ServiceSignatureProvider::class
```

此扩展包含一个签名静态类用于签名和一个验证签名的中间件

## 签名

`Signature::genArray($service, array $query=[]);`

- *$service* 需要调用的服务，根据此参数从配置文件查找对应的 `service_key` 和 `service_secret`
- *$query* 传递的 url 参数

返回签名后的 query 数组。示例：

```
return Signature::genArray('cas', $query = [
            'foo' => 'bar',
        ]);
```

将返回：

```
array:5 [
  "foo" => "bar"
  "service_key" => "caskey"
  "timestamp" => 1484029429
  "nonce" => "0SBliH0vT4"
  "signature" => "de203eac8b2cec03ac404ec3ed6d5bcd"
]
```

如果你希望直接返回 http_build_query 后的字符串，可调用 `Signature::genString($service, array $query=[])`，则如上示例将返回：

```
"foo=bar&service_key=caskey&timestamp=1484029666&nonce=YkQFuVQFMU&signature=773c3d14c082b7a0ab14dcb2f9c471bf"
```

## 验证签名（中间件）

在 `app/Http/Kernel.php` 文件中注册 `Uicosp\ServiceSignature\VerifySignature::class`, 并对需要验证签名的路由添加本中间件。