# Kyojin JWT Example Repo

This repo is a simple example of how to implement [Kyojin JWT](https://github.com/tahajaiti/jwt) — a Laravel package that eases JWT Auth implementation in Laravel 11/12x Apps

## What this repo is about

- User registration with automatic token generation
- JWT authentication using middleware
- Token decoding and accessing the authenticated user

##  Getting Started

### 1. Clone this repo

```bash
git clone https://github.com/youcode-collab/LARAVEL-12-JWT_Example
cd LARAVEL-12-JWT_Example
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Set up your environment

Copy the `.env.example` to `.env`:

```bash
cp .env.example .env
```

Set up your DB in the `.env` file, then run:

```bash
php artisan migrate
```

### 4. Install the Kyojin JWT Package

```bash
composer require tahajaiti/jwt
```

Run the setup command:

```bash
php artisan jwt:setup
```

This will:
- Add JWT configs to `.env`
- Publish the `config/jwt.php` file
- Clear the config/cache

### 5. Serve it

```bash
php artisan serve
```

##  How It Works

### Registration

Hit the `POST /api/register` endpoint:

```json
{
  "name": "Test User",
  "email": "test@hhhhhh.com",
  "password": "kalimatsir"
}
```

Response:

```json
{
  "user": { ... },
  "token": "your-token"
}
```

###  Authenticated Route

Use the token from registration in your request headers:

```
Authorization: Bearer your-jwt-token
```

Then call `GET /api/me` to get the current user and payload:

Response:

```json
{
  "user": { ... },
  "payload": {
    "sub": 1,
    "role": "user",
    "exp": 1728546548
  }
}
```

---

## Code Breakdown

### `routes/api.php`

```php
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('jwt')->group(function () {
    Route::get('/me', function (Request $request) {
        $token = $request->bearerToken(); //accessing the token from the header
        $payload = JWT::decode($token); //decoding the token for the payload

        return response()->json([
            'user' => Auth::user(), //accessing the logged in user based on the token
            'payload' => $payload,
        ]);
    });
});
```

### `AuthController.php`

```php
public function register(Request $request) {
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password
    ]);

    $token = $user->createToken(); //creating token

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}
```

### User Model Setup

Don't forget to use the trait in your `User.php` model:

```php
use Kyojin\JWT\Traits\HasJWT;

class User extends Authenticatable
{
    use HasJWT;

    public function payload(): array
    {
        return [
            'role' => $this->role ?? 'user' //your custom payload values
        ];
    }
}
```

---

## Want to Learn More?

Check out the full [Kyojin JWT Package Documentation](https://github.com/tahajaiti/jwt) for more info on exception handling, facades, and validation methods.
