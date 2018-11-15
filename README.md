# Magento 2 Japan
This repository contains set of modules required to provide a necessary user experience for Magento 2 at Japanese market.

Initial implementation of modules listed here provided by [Veriteworks Inc.](https://veriteworks.co.jp/)

Any Magento Community member are welcome to join to the project.

**Goal:** Adopt Magento 2 to Japanese market

**Agile Board:** https://app.zenhub.com/workspace/o/magento/magento2-jp

**Slack:** [#japanese-localization](https://magentocommeng.slack.com/messages/CB3DG6HFH)

**Release strategy:** Modules from this repository will be available as separate extensions to Magento 2 with possibility to install them with Composer metapackage. All development targeted to Magento 2.3 and will be compatible with all further Magento releases.

If implementing some feature requires changes in Magento 2 core these changes should be delivered with pull request to [magento/magento2-l10n](https://github.com/magento/magento2-l10n) and will be merged as soon as all tests are green and approved by reviewer. Contribution to [magento/magento2](https://github.com/magento/magento2) is also acceptable but in this case pull request will be processed in general queue.

**Committing changes:** It is highly encouraged for this project to create feature branches inside this repo. Any contributor should be able to do that.

**Companion project:** Repository [magento/magento2-l10n](https://github.com/magento/magento2-l10n) should be used to deliver fixes to Magento core required by this project.

## Installation from Git

To install Magento with Japanese modules please follow next steps:

1. Clone this repository outside from Magento Open Source directory

```
git clone git@github.com:magento/magento2-jp.git
cd magento2-jp
```

2. Optionally, switch to feature branch
```
git checkout -b <local_branch> origin/<feature_branch>
```

3. Clone main Magento 2 repository https://github.com/magento/magento2

```
cd ../
git clone git@github.com:magento/magento2.git
cd magento2
```

4. Add repository with localization core improvements https://github.com/magento/magento2-l10n as remote repository and fetch data
```
git remote add l10n git@github.com:magento/magento2-l10n.git
git fetch l10n
```
Optionally, checkout local branch from l10n repository

```
git checkout -b <local_branch> l10n/<feature_branch>
```

5. Link Magento 2 Japanese modules to Magento 2 Open Source

```
ln -s ../../../magento2-jp ./app/code/MagentoJapan
```

You may need to change file paths to match your setup

7. Add `app/code/MagentoJapan` to local ignore list in Magento Open Source repository
```
echo "app/code/MagentoJapan" >> ./.git/info/exclude
```

8. Now you have Magento Open Source code with Magento Japan modules ready to be installed as described at [DevDocs](https://devdocs.magento.com/guides/v2.2/install-gde/install/web/install-web-sample-data-clone.html#samp-data-perms).

If you already have Magento installed you need enable Japanese modules and upgrade your installation:
```
php bin/magento module:enable --all
php bin/magento setup:upgrade
```

## Contribution

To participate in the project and contribute you need to create local installation as described in section above.

We encourage to contribute to https://github.com/magento/magento2-jp and https://github.com/magento/magento2-l10n by pushing your local changes to feature branch. Once feature or bugfix is completed or you would like to start communication on it please create pull requests from a feature branch to `2.3-develop` branch.

If you don't have permissions to push new branch in https://github.com/magento/magento2-jp or https://github.com/magento/magento2-l10n please contact Magento Community Engineering representative Volodymyr Kublytskyi by email vkublytskyi@magento.com or by private message in Magento Community Engineering Slack.

Optionally, you may fork https://github.com/magento/magento2-jp repository and create pull requests from it.

We recommend not to fork https://github.com/magento/magento2-l10n and use a fork of main Magento Open Source repository (https://github.com/magento/magento2) instead.

## Goals

### MVP

**M**inimum **V**iable **P**roduct targeted to support Magento Open Source 2.3.x (with sample data) single store with `ja_JP` locale and `JPY` currency with following features:
- [ ] Full translations to Japanese
- [ ] Valid rounding of JPY currency
- [ ] Katakana for names
- [ ] Valid names format for storefront and admin (<lastname> <firstname> <politness suffix>)
- [ ] Perfectures (regions) listed in order from North to South
- [ ] Autocomplete of address fields based on ZIP code
- [ ] Possibility to configure store address from Admin Panel
- [ ] Valid configuration of ElasticSearch index (Kuromoji)
- [ ] Possibility to generate PDF files with Japanese multibyte characters
- [ ] Cache on delivery as payment method
- [ ] Install with single composer dependency (e.g. `magentojapan/common`)

MVP optional features:
- [ ] Preset of configuration options (locale, currency, measurement units, etc.) applied during installation
- [ ] Valid date format in admin
- [ ] Possibility to specify delivery time
- [ ] Payment Methods (GMO Payment Gateway, Veritrans, Sony Payment Service)
- [ ] Support of main delivery companies (Yamato, Sagawa)
- [ ] Social logins (Yahoo! Japan, mixi, twitter, facebook, Instagram, Google)