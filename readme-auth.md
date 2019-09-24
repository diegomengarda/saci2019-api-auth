# Autenticação com o JWT

### Instalar a biblioteca
```
composer require "tymon/jwt-auth:1.0.*"
```

### Rodar o comando para criar a chave de criptografia
```
php artisan jwt:secret
```

### Editar o arquivo app/Http/Kernel.php e adicionar a linha abaixo no atributo $routeMiddleware
```php
'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
```

### Adaptar o model User com esses métodos e interfaces
```php
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }
  
  public function getJWTCustomClaims()
  {
    return [];
  }
}
```

### Alterar o arquivo de configuração do Auth para usar o jwt config/auth.php
```php
'api' => [
    'driver' => 'jwt', # Alterar essa linha
    'provider' => 'users',
],
```

### Criar o controller de autenticação do User
```
php artisan make:controller UserController
```

### Criar o método para autenticação no UserController
```php
public function login(Request $request) {
    $credentials = $request->only(['email', 'password']);
    
    if (!$token = auth('api')->attempt($credentials)) {
        return response(['error' => 'Login ou Senha incorretos'], 401);
    }
    
    return response([
        'token' => $token
    ]);
}
```

### Criar a rota de autenticação fora do grupo do middleware no routes/api.php
```php
Route::post('login', 'UserController@login');

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('user', 'UserController@userLogged');
    
    // Rotas autenticadas
});
```


### Substituir a função render do arquivo app/Exceptions/Handler.php para tratar os erros de autenticação
```php
public function render($request, Exception $exception)
{
    if ($exception instanceof UnauthorizedHttpException) {
        $preException = $exception->getPrevious();
        if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response(['error' => 'TOKEN_EXPIRED'], 400);
        } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            return response(['error' => 'TOKEN_INVALID'], 400);
        } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
            return response(['error' => 'TOKEN_BLACKLISTED'], 400);
        }
    }
    if ($exception->getMessage() === 'Token not provided') {
        return response(['error' => 'Token not provided'], 400);
    }
    return parent::render($request, $exception);
}
```
