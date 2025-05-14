---
title: "概要"
layout: ../../../layouts/Default.astro
---

もし Grav またはその拡張機能に関連するセキュリティ上の問題を発見されたら、コア・チームにメールしてください（[contact@getgrav.org](mailto:contact@getgrav.org)）。速やかに対応します。

コア・チームがその問題を調査し、関係者に連絡して解決するまでは、その問題を公表すべきではありません。これは、 GitHub 上でも、 Discord 上でも、 Discourse フォーラム上でも、公表は待ってください。また、その問題が、 Grav ユーザーにとってセキュリティ上の脅威でない場合は、 GitHub の [issue](https://github.com/getgrav/grav/blob/develop/CONTRIBUTING.md#bug-reports) として送信していただくのが良いでしょう。判断がつかない場合は、ご連絡いただければ、どちらの問題に属するかの判断をお助けします。

<h2 id="submitting-a-report">レポートの送信</h2>

Grav のコアや、拡張機能のひとつに、潜在的な脆弱性を発見した場合は、適切な注意のもと報告をお願いします：

1. Grav の **バージョン番号** とインストールされている拡張機能、および、問題に関連する **コンポーネント** を含めてください。
2. 脆弱性を **詳細かつ簡潔に** 記述することで、その原因の探索にかかる時間が短縮されます。
3. 脆弱性が起きる **環境を再現する** ために必要なステップを正確に書き留めてください： system.yaml ファイルに何が設定されているか、どんなコンテンツが作成されているか、そしてどのようなシステム設定が適用されているか？ などです。
4. もし可能ならば、脆弱性の原因と、開発者が再構築してセキュリティ対応できる **パッチの適用** 方法を記述してください。

<h3 id="responsible-disclosure">責任ある開示</h3>

Grav は、発見された脆弱性の報告のため、 _責任ある開示_ モデルに従います。これはつまり、問題が発見され、テストされ、実証に成功した場合に、その脆弱性が公開される前に、開発者には、一定期間のパッチ適用期間が与えられるべき、ということです。なぜなら、報告された問題に対する解決策の発見とテストには、時間がかかるうえに一刻を争うからです。 Grav は、オープンソースプロジェクトであり、作者はそれのみに無限に時間をかけられるわけではありません。よって、関連するコードに関する知識を有する場合は、問題解決やパッチ適用の方法も、ご提案いただくことを推奨しています。

<h2 id="process-of-resolution">解決のプロセス</h2>

報告いただいた内容が正確で、新しいセキュリティ問題が再現された場合、コア・チームは可能な限り速やかに対応します。対応が完了すると、問題とその解決策は、 [レポートの公開のリポジトリ](../06.reports/) に掲載されます。報告者は、お名前と、オプションで web サイト/ソーシャルメディアのプロフィールへのリンクがクレジットされますが、ご希望があれば、仮名または "匿名報告者" としてクレジットされることもできます。

レポートと問題は、問題が解決されるまでは、非公開のままです。拡張機能のメンテナーが、問題をタイムリーに解決できない場合、その拡張機能は、解決されるまで Grav パッケージマネージャーから削除されます。

<h2 id="supported-versions">サポートされるバージョン</h2>

Grav の現在の `major.minor` バージョンのみが、サポート対象です。つまり、パッチは `major.minor.patch` バージョンに実装されます。 Grav の古いバージョンに戻ることはありません。インストールを最新状態にすることは重要です。多くの変更は、明らかなセキュリティ上の理由が無かったとしても有益です。

<h2 id="risk-levels">リスクのレベル</h2>

ソフトウェアとして、 Grav には 5つのリスクレベルがあります。

- Highly Critical （非常に重大）
- Critical （重大）
- Moderately Critical （中程度に重大）
- Less Critical （それほど重大ではない）
- Not Critical （重大ではない）

これらは、 [National Institute of Standards and Technology](https://www.nist.gov/) (NIST) による "[Common Misuse Scoring System](https://www.nist.gov/news-events/news/2012/07/software-features-and-inherent-risks-nists-guide-rating-software)" (CMSS) をベースに計算されます。 Grav 用の簡単に利用できる計算機が無い場合は、 Drupal の [RiskCalc](https://security.drupal.org/riskcalc) をご利用ください。 ([注意](https://www.mydropwizard.com/blog/understanding-drupal-security-advisories-risk-calculator))
