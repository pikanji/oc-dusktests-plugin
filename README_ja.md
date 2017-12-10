# OctoberCMS Laravel Dusk Plugin

Laravel Dusk テストフレームワークを[OctoberCMS](http://octobercms.com/) のプロジェクトで利用可能にします。

## 使い方
### インストール
インストール方法は下記の中から選べます
* OctoberCMS UI から（対応予定）
* Composer
* 手動で`git clone`


#### With Composer
プロジェクトのcomposer.jsonに下記を追加してください。
```
{
    "require": [
        ...
        "pikanji/dusktests-plugin": "dev-master"
    ],
```

プロジェクトルートから下記を実行します。
```
composer update
```

#### Manual Git Clone
プロジェクトの `plugins` ディレクトリに `pikanji` ディレクトリを作成し、その中で `git clone` でソースコードを取得します。
```
cd plugins
mkdir pikanji
cd pikanji
git clone git@github.com:pikanji/oc-dusktests-plugin.git dusktests
```



