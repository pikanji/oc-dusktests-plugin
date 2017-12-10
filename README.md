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
git clone git@github.com:pikanji/oc-dusktests-plugin.git dusktests
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
execute below at the root of your project.
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
If you are using docker container to run web server for your project, you need to start selenium server
(or standalone chromedriver) on a machine (or container) where testing browser is installed (for example your local machine).

Below is the instruction to use selenium + chromedriver on Mac with your project running in Docker container.

#### Install Selenium & Chrome Driver
Even though chromedriver can work as standalone without selenium, we use selenium together so that we can add other drivers to test with other browsers.

Install selenium. Execute following **in the host machine**.
```
brew update
brew install selenium-server-standalone
selenium-server --version
```

Install chromedriver. Execute following **in the host machine**.
```
brew install chromedriver
```

### Modify DuskTestCase
Default `tests/DuskTestCase.php` automatically starts chromedriver in where you execute tests. So, you need to comment
that one line out. Also, you need to let DuskTestCase know which IP the selenium server is accessible with.

Modify `tests/DuskTestCase.php`.
* Comment out `static::startChromeDriver();`
* Change URL parameter of `RemoteWebDriver::create` invocation to `http://<your_host_machine_IP>:4444/wd/hub`.  
  IP can be checked by `ifconfig` on your host machine.

Example
```
public static function prepare()
{
    //static::startChromeDriver();
}

/**
 * Create the RemoteWebDriver instance.
 *
 * @return \Facebook\WebDriver\Remote\RemoteWebDriver
 */
protected function driver()
{
    $options = (new ChromeOptions)->addArguments([
        '--disable-gpu',
        '--headless'
    ]);

    return RemoteWebDriver::create(
        'http://192.168.1.115:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        )
    );
}
```

#### Start Selenium
Execute below in your **host machine**. This should automatically detect chromedriver.
```
selenium-server
```

#### Run Tests
Execute below at the root of your project in the container.
```
php artisan dusk
```

#### Fix Host's IP Address
Every time the IP address of your host machine changes, you need to change the IP parameter for `RemoteWebDriver::create()`.
To avoid this, set custom IP address to the loop back interface of your host machine.
```
sudo ifconfig lo0 alias 10.200.10.1/24
```

This will get deleted when the machine is restarted.
So we create a shell script to register this IP to lo0 and let it be executed every time machine starts up.  
ref: https://joppot.info/2017/05/03/3908

Create shell script in ~/.lo0ip.sh (or name it whatever you like).
```
#!/bin/bash
sudo ifconfig lo0 alias 10.200.10.1/24
```

Make it executable.
```
chmod a+x ~/.lo0ip.sh
```

Add it to LoginHook.
```
sudo defaults write com.apple.loginwindow LoginHook ~/.lo0ip.sh
```

Check registration
```
sudo defaults read com.apple.loginwindow LoginHook
```

Now you can fix the IP address used in the parameter of `RemoteWebDriver::create` to `10.200.10.1`.
```
protected function driver()
{
    ...
    return RemoteWebDriver::create(
        'http://10.200.10.1:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
    ...
}
```
