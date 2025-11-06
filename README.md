# PHP Try-Catch Helper

tryIt() is a simple, yet powerful PHP helper function that streamlines error handling throughout your PHP application.

## Description

This helper function wraps operations in a try-catch block and returns a structured response containing either the result or the captured exception. It provides a consistent approach to error handling and simplifies code that needs to handle potential exceptions.

## Features

- Execute any callable and get a standardized response structure
- Option to return results as array
- Configurable error throwing behavior
- Built-in error logging with detailed exception information
- Reduces boilerplate code in exception handling
- Less cluttered code with multiple try-catch statements inside each other

## Add to Autoload:

### Laravel Example:

In Laravel, custom helper files are not loaded automatically. You need to register the file with the Composer autoloader. Open the `composer.json` file in the root of your project and add the path to your helper in the autoload section:

```json
"autoload": {
    "files": [
        "app/Support/Helpers/tryItHelper.php"
    ],
    "psr-4": {
        "App\\": "app/"
    }
}
```

After editing `composer.json`, run the command in the terminal to update the autoloader:

```bash
composer dump-autoload
```

This ensures that the helpers.php file is automatically included in all requests.

## Usage

```php
// Basic usage
$result = tryIt(function () {
    // Your code that might throw an exception
    return callSomeFunction();
});

if ($result->error) {
    // Handle error
} else {
    $user = $result->data;
}

// Automatically throw exceptions instead of returning them
tryIt(static fn () => criticalOperation(), throwError: true);

// Disable automatic error logging
tryIt(fn () => someOperation(), logErrors: false);
```

### Sometimes you want to do something when your call fails

Use `callbleOnError` param. It will return `callbleOnErrorReturn` with:
- `null`: when there is no prior error (default)
- `mixed`: the result of the callbleOnError() (if any)
- `Throwable`: if callbleOnError() fails

```php
$result = tryIt(fn () => someErrorWillThrow(), callbleOnError: fn () => doAnotherThing())

if($result->error !== null && $result->callbleOnErrorReturn instanceof Throwable)
    doSomeThingWithThisError();
```

Perfect for simplifying error handling in Laravel applications while maintaining clean, readable code.
