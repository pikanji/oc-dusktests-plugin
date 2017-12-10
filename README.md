# OctoberCMS Laravel Dusk Plugin

[日本語版はこちら](./README_ja.md)

This plugin enables to use Laravel Dusk test framework in [OctoberCMS](http://octobercms.com/) projects.

## Usage
### Installation
Installation options are;
* OctoberCMS UI (not yet available now)
* Composer
* Manual `git clone`


#### With Composer
Add below to the composer.json of your project.
```
{
    "require": [
        ...
        "pikanji/dusktests-plugin": "dev-master"
    ],
```

Execute below at the root of your project.
```
composer update
```

#### Manual Git Clone
In the plugins directory of your project, create `pikanji` directory and simply execute `git clone` in it.
```
cd plugins
mkdir pikanji
cd pikanji
git clone git@github.com:pikanji/oc-dusktests-plugin.git
```



