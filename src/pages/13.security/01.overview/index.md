---
title: "概要"
layout: ../../../layouts/Default.astro
---

もしGravや、その拡張機能に関連するセキュリティ上の問題を発見されたら、コア・チームへEメールをしてください（[contact@getgrav.org](mailto:contact@getgrav.org)）。速やかに対応します。

コア・チームがその問題を検討し、解決のために関係者に連絡を取るまでは、その問題を公表しないでください。それは、GitHub上でも、Discord上でも、Discourseフォーラム上でも、公表は待ってください。また、その問題が、Gravユーザーにとってセキュリティ上の脅威でない場合は、GitHub の [issue](https://github.com/getgrav/grav/blob/develop/CONTRIBUTING.md#bug-reports) として送信していただくのが良いでしょう。判断がつかない場合は、ご連絡いただければ、どちらの問題に属するかの判断をお助けします。

<h2 id="submitting-a-report">レポートの送信</h2>

Grav のコアや、拡張機能のひとつに、潜在的な脆弱性を発見した場合は、適切な注意のもと報告をお願いします：

1. Include the **version numbers** of Grav and any installed extensions, as well as **which component** the issue relates to.
2. Describe the vulnerability in a **detailed and concise manner**, so that less time is spent on searching for its source.
3. Write down exact steps needed to **reproduce the environment** in which the vulnerability occurs: What settings are set in system.yaml, what content is created, and what system settings applied?
4. If possible, describe the source of the vulnerability and how to **patch it** so developers can both reconstruct and secure it.

<h3 id="responsible-disclosure">責任ある情報公開</h3>

Grav follows the _responsible disclosure_ model for submittal of discovered vulnerabilities. This means that once an issue is discovered, tested, and successfully demonstrated, the developer(s) should be allowed a period of time to patch the vulnerability before it is publicly disclosed. This is because finding and testing solutions to reported issues are time-consuming and time-sensitive, and Grav is an open-source project whose authors do not have unlimited time to dedicate to it. It is therefore recommended that you also propose how to solve the issue or patch it, if you have knowledge of the relevant code.

<h2 id="process-of-resolution">解決のプロセス</h2>

If your report is accurate and a new security issue is reproduced, the core team will address it as soon as possible. When this is done, the issue and its solution will be included in the [public repository of reports](../06.reports/). You will be credited by name and with an optional link to your website/social media profile, but if you prefer you can request it be credited to a pseudonym or "anonymous reporter".

Reports and issues are kept private until the issue is resolved. In the case that the maintainer of an extension fails to address the issue in a timely manner, the extension is removed from the Grav Package Manner until it is resolved.

<h2 id="supported-versions">サポートされるバージョン</h2>

Only the current `major.minor` version of Grav is supported. This means that patches are implemented in `major.minor.patch`, but not regressively backwards for older versions of Grav. Keeping your installation up-to-date is important, and many changes are beneficial even if not explicitly needed from a security perspective.

<h2 id="risk-levels">リスクのレベル</h2>

There are five levels of risk involved with Grav as a software:

- Highly Critical
- Critical
- Moderately Critical
- Less Critical
- Not Critical

These are calculated based on the "[Common Misuse Scoring System](https://www.nist.gov/news-events/news/2012/07/software-features-and-inherent-risks-nists-guide-rating-software)" (CMSS) by the [National Institute of Standards and Technology](https://www.nist.gov/) (NIST). For lack of an easily available calculator for Grav, use Drupal's [RiskCalc](https://security.drupal.org/riskcalc) ([notes](https://www.mydropwizard.com/blog/understanding-drupal-security-advisories-risk-calculator)).
