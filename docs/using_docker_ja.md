### Dockerコンテナを使用している場合
もし、DockerコンテナのWebサーバを使用している場合は、Seleniumサーバ（またはスタンドアロンchromedriver）をテストに使用するブラウザが
インストールされているマシン（例えばローカルマシン）で走らせる必要があります。

以下は、Dockerコンテナ内でWebサーバを実行していて、MacでSelenium + chromedriverを起動してテストする方法です。

### Selenium & Chrome Driverのインストール
chromedriverはSeleniumなしでも使用できますが、他のブラウザを使用することも考えて、Seleniumと合わせて使用します。

下記をホストマシンで実行して、Seleniumをインストールします。
```
brew update
brew install selenium-server-standalone
selenium-server --version
```

下記をホストマシンで実行して、chromedriverをインストールします。
```
brew install chromedriver
```

### DuskTestCase の修正
デフォルトのDuskTestCaseの実装では、テスト実行場所のchromedriverを自動で起動してそこに接続するようになっているので、
テストをブラウザが入っていないコンテナ内で実行する場合は、ブラウザがあるローカルマシンのselenium（またはchromedriver）に接続する必要がある。

`tests/DuskTestCase.php` を下記のように修正します。
* `static::startChromeDriver();` をコメントアウト。
* Change URL parameter of 
* `RemoteWebDriver::create` の呼び出しの際のURLを `http://<ホストマシンのIP>:4444/wd/hub` に変更。
  ホストマシンのIPはホストマシン上で `ifconfig` で確認できるIPです。

例
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

### Seleniumの起動
ローカルマシンで下記を実行して、Seleniumを起動します。chromedriverは自動的にロードされます。
```
selenium-server
```

### Run Tests
プロジェクトが走るコンテナ内のプロジェクトルートで下記を実行することでテストを走らせることができます。 
```
php artisan dusk
```

### ホストマシンに固定IPを設ける
上記で、ホストマシンのIPを`RemoteWebDriver::create`呼び出し時のパラメータに使用しているが、マシンのIPが変わるたびにソースコードを修正するのは面倒です。
そこで、ホストマシンの`lo0`インタフェースにエイリアスIPを設定して変わらないようにする。[こちら](https://joppot.info/2017/05/03/3908)を参考にさせてもらいました。

下記をローカルマシンで実行することで、lo0のIPを`10.200.10.1`にして、これをテストで使うことができます。
```
sudo ifconfig lo0 alias 10.200.10.1/24
```

ただ、マシンを再起動すると設定が消えてしまうので、起動時に実行されるスクリプトで上記コマンドを実行させます。


ファイル名はなんでも良いが、下記の内容のスクリプトを作成。とりあえず`~/.lo0ip.sh`とします。
```
#!/bin/bash
sudo ifconfig lo0 alias 10.200.10.1/24
```

実行権限をつけます。
```
chmod a+x ~/.lo0ip.sh
```

ログインフックに追加します。
```
sudo defaults write com.apple.loginwindow LoginHook ~/.lo0ip.sh
```

登録を確認します。
```
sudo defaults read com.apple.loginwindow LoginHook
```

lo0のIPを設定したら、それをRemoteWebDriver::createの呼び出しで指定すれば、もう修正する手間がなくなります。
```
protected function driver()
{
    ...
    return RemoteWebDriver::create(
        'http://10.200.10.1:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
    ...
}
```
