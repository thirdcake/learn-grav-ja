---
title: "Gitによるデプロイ"
layout: ../../../layouts/Default.astro
---

開発環境とサーバ環境で、バージョン管理システムの [Git](https://git-scm.com/) を使っているなら、ホスト式のGitサービスである [GitHub](https://github.com) や、[Gitlab](https://about.gitlab.com/) を使ったシンプルなワークフローを構築できます。Gitにやそのクライアントツールに親しんでいるなら、試してみてください。

Its benefits include:
* it's **cleaner**: you only need to issue a few command line instructions and these can be automated to any degree
* **more reliable**: you don't need to remember which files to upload and you can be sure that you only escalate the changes you want (particularly useful when you only want some changes uploaded within files)
* **safety**: by using a cloud host for your canonical repo ("origin"), (versioned) source backups come free; you can even manage your tasks using issues.

<h2 id="setting-up">セットアップ</h2>

A Git-based workflow requires some setup. Here is a broad overview of the configuration. Depending on whether you want to commit folders that contain third party code like `plugins`, there may be some more steps when you first set it up on your server.

* On your development environment, your `user` folder is a Git repository.
* Your `user` folder repository is also hosted in the cloud. Choose a provider that supports private repositories if you don't want to share your code with the world.
* Your hosted copy is your local and server environment's "remote" `origin`.
* Push changes to your Grav site from the local environment to `origin` on your Git cloud host.
* On your server, you have Grav installed and its `user` folder is a clone of your remote repository.
* When you are ready to update your Grav site on your server, use Git to pull from your remote's `origin`.

<h2 id="updates">アップデート</h2>

After intial setup, you only really need to perform two steps after each significant update:
* push from your local environment,
* pull changes to your server.

<h2 id="extending-your-setup">セットアップの拡張</h2>

If you want more advanced automation, you can set up [Git Hooks](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks) or use a feature like [Github's webhooks](https://docs.github.com/en/developers/webhooks-and-events/webhooks/about-webhooks). You could also integrate content changes from web editors making edits on their own installations through the Admin console. You can keep (almost) immutable records of what is published using Git tags.

The tools available support all kinds of multi-environment workflows and automations.

> [!Tip]  
> You can also exploit Git for your content workflow using the [Git Sync plugin](https://github.com/trilbymedia/grav-plugin-git-sync), so that your content editors can deploy changes via the Administration console.

Here is a suggestion for your `.gitignore` file in your `user` folder repository. This will help keep your deployment clean:

```git
accounts/*
!accounts/.*
data/*
!data/.*
languages/*
!languages/.*
plugins/*
!plugins/.*
themes/*
!themes/.*
!themes/MY_CUSTOM_THEME/
**/config/security.yaml
```

> [!Info]  
> If you are using a custom or inherited theme that you want to include in your source control, subsitute `MY_CUSTOM_THEME` above with the theme name. Consider doing the same for any site-specific custom plugins.

