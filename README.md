# Haxe PHP Watcher

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Example](#example)

## Requirements

1) PHP >= 7.4
2) Haxe Compiler (for local development only)
3) Composer

## Installation

- `composer require serafim/haxe-watcher`

## Configuration

Configuration using `extra` section in your `composer.json` file:

```json5
{
    "extra": {
        "haxe": {
            // Path to Haxe Compiler.
            //  - Information from the PATH environment variable 
            //    is read by default ("haxe" binary).
            "compiler": "/usr/bin/haxe",

            // Path to source files.
            //  - Information from the "autoload" (psr-0, psr-4, classmap) 
            //    section is read by default.
            "src": [
                "path/to/sources"
            ],
    
            // Number of milliseconds to check file updates.
            //  - 60000 by default
            "watch": 60000,
    
            // The name of the configuration file generated for 
            // the Haxe Compiler.
            //  - "build.hxml" by default
            "config": "test.hxml"
        }
    }
}
```

## Usage

#### Haxe Compiler Version

```bash
$ composer haxe:version
```

#### Run Haxe Watcher

```bash
$ composer haxe:watch
```

## Example

### Project Structure

```
- app/
    - Main.hx
- composer.json
```

### Instruction

1) Create `composer.json` with following code

```json5
{
    "require": {
        "serafim/haxe-watcher": "*"
    },
    "autoload": {
        "psr-4": {
            "app": "app"
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

2) Create `app/Main.hx` with following code:

Main.hx File

```haxe
module app;

class Main {
    static function main() {
        Sys.println("test");
    }
}
```

3) Run watcher: `composer haxe:watch`
