# Haxe PHP Watcher

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

```bash
$ composer haxe-watch
```

## Example

An example project:

#### Project Structure

```
- app/
    - Main.hx
- composer.json
```

#### Composer File

```json5
{
    "require": {
        "serafim/haxe-watcher": "*"
    },
    "autoload": {
        "psr-4": {
            // Note!
            //   1) Haxe can NOT compile non PSR-0 namespaces.
            //   2) All namespaces (modules) should be in lowercase.
            "app": "app"
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

#### Main.hx File

```haxe
module app;

class Main {
    static function main() {
        Sys.println("test");
    }
}
```

#### Running

```bash
$ composer haxe-watch
```
