---
title: DigitalOcean
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-13'
---

もしかすると、すべての VPS プロバイダで最も人気で、最も幅広く使われているであろう [DigitalOcean](https://www.digitalocean.com/) は、 VPS オプションを提供しています。 **月額 $5 で 1CPU, 1024MB システム** から、 月額 $960 で 32 CPU, 192 GB のセットアップまであり、 [DigitalOcean](https://www.digitalocean.com/) はあなたとともにスケールするソリューションを持っています。すべてのサーバーは **RAID SSD ドライブ**, **モダンな 6-core ハードウェア**, **KVM 仮想化**, そして信頼できる **Tier-1 帯域幅** でビルドされており、最大限のパフォーマンスを約束します。 Grav ベースのサイトをホスティングするには、すばらしいオプションです。

![](digitalocean.png)

After creating an account and depositing some credit into it, you can get started.  DigitalOcean let's you create **Droplets** that represent a VPS instance.  You simple click the **Create Droplet** button in your Control Panel, and fill in the form:

![](step-1.png)

Simply pick a name for your Droplet, and **choose a size** based on price and server needs.  Grav will run fine on any configuration even the base $5/mo option will run Grav quickly and efficiently.

![](step-2.png)

Next, **select a Region** where your VPS will be located.  It's best to pick a region that is going to serve your target audience the best.  If the server is for development purposes only, pick one that is located closest to you.

![](step-3.png)

Lastly you will need to select an Image to install.  DigitalOcean lets you choose from a wide variety of stock Linux distributions, as well as complete Applications and even prior saved snapshots.  For the purpose of this guide, we'll install the latest **Ubuntu 18.04 LTS** which is very popular and very well supported.

You can leave all the other options at their defaults.  After clicking **Create Droplet** your Droplet will be created within 55 seconds, and you will see it listed in your list of Droplets.  You should receive an email with your root password. Clicking on the Droplet you just created you will see various options.

![](droplet.png)

The **Access** tab in the Droplet Manager allows you to quickly log on to your instance, but using SSH is a more enjoyable experience. Public key authentication is also recommended, and DigitalOcean has great [SSH public key authentication documentation](https://www.digitalocean.com/community/tutorials/how-to-use-ssh-keys-with-digitalocean-droplets) that walks you through the steps required.

---

[plugin:content-inject](../05.ubuntu-18.04/)

