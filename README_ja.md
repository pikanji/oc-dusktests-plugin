# OctoberCMS Laravel Dusk Plugin

Laravel Dusk テストフレームワークを[OctoberCMS](http://octobercms.com/) のプロジェクトで利用可能にします。

## 使い方
### インストール
インストール方法は下記の中から選べます
* OctoberCMS UI から（対応予定）
* Composer
* 手動で`git clone`


#### Composer
プロジェクトのcomposer.jsonに下記を追加してください。
```
{
    "require-dev": [
        ...
        "pikanji/dusktests-plugin": "dev-master"
    ],
```

プロジェクトルートから下記を実行します。
```
composer update
php artisan dusk:install
```

#### 手動でGit Clone
依存関係はcomposerでインストールする必要がありますが、本プラグイン自体はプロジェクトのcomposer.jsonを変更せずにインストールすることもできます。
プロジェクトの `plugins` ディレクトリに `pikanji` ディレクトリを作成し、その中で `git clone` でソースコードを取得します。
```
cd plugins
mkdir pikanji
cd pikanji
git clone git@github.com:pikanji/oc-dusktests-plugin.git dusktests
```

プロジェクトルートから下記を実行します。
```
composer update
php artisan dusk:install
```

### テストの実行
Duskについてくるサンプルテスト(`tests/Browser/ExampleTest.php`)を実行してDuskのセットアップをテストできます。

#### サンプルテストクラスの修正
サンプルテスト `ExampleTest.php` は "Laravel" という文字列がページに表示されるかをチェックします。
demoテーマを使用していると仮定して、テストが成功するようにこの文字列を "October CMS" に変更します。
```
public function testBasicExample()
{
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
                ->assertSee('October CMS');
    });
}
```

#### テスト実行
テスト用のWebサーバが、Chromeがインストールされているローカルマシンで起動していると仮定して、
プロジェクトルートで下記を実行するだけで、テストが実行されます。もしDockerを使用している場合は、READMEに加え[こちら](./docs/using_docker_ja.md)も参考にしてください。
```
php artisan dusk
```

テストの完了までに2分など時間がかかる可能性があります。
テストに失敗した場合など、自動的にスクリーンショットを撮って、デフォルトでは `tests/Browser/screenshots` に格納してくれます。

#### タイムアウトを伸ばす
もし、テスト実行してタイムアウトエラーになる場合、下記のようにRemoteWebDriver::createに引数を渡してタイムアウト時間を変更できます。
```
return RemoteWebDriver::create(
    'http://192.168.1.115:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
        ChromeOptions::CAPABILITY, $options
    ), 180*1000, 180*1000
);
```

### Dockerコンテナを使用している場合
READMEに加え[こちら](./docs/using_docker_ja.md)も参考にしてください。
