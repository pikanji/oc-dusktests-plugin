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

Execute below at the root of your project:
```
composer require --dev pikanji/dusktests-plugin
php artisan dusk:install
```

#### Manual Git Clone
Although composer is still required to install dependencies, you can install this plugin without adding it to your composer.json.
In the plugins directory of your project, create `pikanji` directory and simply execute `git clone` in it.
```
cd plugins
mkdir pikanji
cd pikanji
git clone git@github.com:pikanji/oc-dusktests-plugin.git dusktests
```

Execute below at the root of your project.
```
composer update
php artisan dusk:install
```

### Executing Tests
Dusk comes with example test (`tests/Browser/ExampleTest.php`). You can test setup by running this test.

#### Fix Example Test
ExampleTest.php checks if a string "Laravel" is found in the loaded web page.
Assuming that your are using fresh copy of demo theme, change "Laravel" to "October CMS" in ExampleTest.php to let the test pass.
```
public function testBasicExample()
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->assertSee('October CMS');
    });
}
```

#### Run Tests
Assuming you are running your web server on your local machine for testing and Chrome browser is installed,
execute below at the root of your project. If you are running web server on Docker container [see here](./docs/using_docker.md)
in addition to this instruction.
```
php artisan dusk
```

Test could be very slow. Leave it for a couple minutes to see if it has progress.

Screenshots are stored in `tests/Browser/screenshots` by default. There might be some configuration to change it.

#### Extending Timeout
If you get timeout error, you can try extending timeout like below in `tests/DuskTestCase.php`.
In my case, 1 minute was not enough. So, I made it 3 minutes.
```
return RemoteWebDriver::create(
    'http://192.168.1.115:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
        ChromeOptions::CAPABILITY, $options
    ), 180*1000, 180*1000
);
```

### Using Docker Container
[See here](./docs/using_docker.md) in addition to this instruction.
