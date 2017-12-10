## Using Docker Container
If you are using docker container to run web server for your project, you need to start selenium server
(or standalone chromedriver) on a machine (or container) where testing browser is installed (for example your local machine).

Below is the instruction to use selenium + chromedriver on Mac with your project running in Docker container.

### Install Selenium & Chrome Driver
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

### Start Selenium
Execute below in your **host machine**. This should automatically detect chromedriver.
```
selenium-server
```

### Run Tests
Execute below at the root of your project in the container.
```
php artisan dusk
```

### Fix Host's IP Address
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
