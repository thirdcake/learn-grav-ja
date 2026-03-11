---
title: 'Proxy 問題'
layout: ../../../layouts/Default.astro
lastmod: '2025-05-09'
---
プロキシーサーバー経由で GPM を実行すると、エラーになることがあります。

cURL により、環境変数（ `http_proxy` や `https_proxy` ）としてプロキシーを設定できます。Grav に変更の必要はありません。

次を確認してください： [http://stackoverflow.com/questions/7559103/how-to-setup-curl-to-permanently-use-a-proxy](http://stackoverflow.com/questions/7559103/how-to-setup-curl-to-permanently-use-a-proxy)

しかしまずは、あなたの環境で `fopen` が有効になっている場合、 php.ini から `allow_url_fopen` を無効化する必要があります。

なぜかとうと、`fopen` が利用可能な場合、 Grav は自動的に `curl` よりも先に `fopen` を使うからです。

