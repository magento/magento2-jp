# Magento 2 Japan
This repository contains set of modules required to provide a necessary user experience for Magento 2 at Japanese market.

Initial implementation of modules listed here provided by [Veriteworks Inc.](https://veriteworks.co.jp/)

Any Magento Community member are welcome to join the project.

**Goal:** Adopt Magento 2 to Japanese market

**Slack:** [#japanese-localization](https://magentocommeng.slack.com/messages/CB3DG6HFH)

**Release strategy:** Modules from this repository are available as separate extensions to Magento 2 with possibility to install them with Composer metapackage `community-engineering/japan-common`. Metapackage of the project is available at [Magento Marketplace](https://marketplace.magento.com/community-engineering-japan-common.html).

All development targeted to Magento 2.3 and will be compatible with all further Magento releases.

**Committing changes:** It is highly encouraged for this project to create feature branches inside this repo. Any contributor should be able to do that.


## Installation from Git

For development purpose Magento Japanese modules as well as Magento Open Source should be installed from Git. Magento Commerce, Magento B2B and Magento Sample Data modules also may be installed locally and should not conflict.

Magento Japanese repository contains only extensions that are part of the project and not contains whole clone of Magento Open Source. This approach is called Magento Developed Extensions (MDE) and introduced to increase Magento modularity.

Development installation of Magento Japanese modules requires several simple addition steps to set up of Magento Open Source development environment:

1. Switch to Magento root folder:
    ```sh
    cd <magento root folder>
    ```

2. Create folder where all MDE extensions will be placed:
    ```sh
    mkdir ext
    ```
    To exclude this folder from Git add it to local ignore list:
    ```sh
    echo ext >> ./.git/info/exclude
    ```
    Now you still be able to modify source code of Magento Open Source and contribute to GitHub. All MDE (as well as any 3rd party module) may be cloned from GitHub or any other Git repository to `ext` folder. 

3. Configure Composer to discover packages from `ext` folder:
    ```sh
    # discover projects that consists from single Composer package/modules
    composer config repositories.extRoot '{"type":"path","url":"ext/*/*","options":{"symlink":true}}'
    
    # discover projects that constains multiple Composer packages/modules
    composer config repositories.extPackage '{"type":"path","url":"ext/*/*/*","options":{"symlink":true}}'
    ```
4. Configure Composer to use development versions of packages:
    ```sh
    composer config minimum-stability dev
    ```
5. Clone source code of Magento Japanese project:
    ```sh
    git clone git@github.com:magento/magento2-jp.git ext/magento/magento2-jp
    ```
    We highly recommend to use `<vendor>/<project>` folders inside `ext` folder to avoid naming conflicts.   
6. Add Magento Japanese project to your Magento installation:
    ```sh
    composer require community-engineering/japan-common:@dev
    ```
7. To avoid unintentional commit of modified `composer.json` and `composer.lock` files skip them:
    ```sh
    git update-index --skip-worktree composer.json
    git update-index --skip-worktree composer.lock
    ```
    If in future you would like to contribute changes to these files you may unskip files:
    ```sh
    git update-index --no-skip-worktree composer.json
    git update-index --no-skip-worktree composer.lock
    ```

Now Magento Japanese modules are visible to your development Magento instance and you may modify, pull or push source code from `ext/magento/magento2-jp` folder. 

To complete installation process you should install or upgrade Magento to enabled added modules.
If you use CLI for Magento installation you may now skip locale, timezone and currency options as they will be provided by [JapaneseDefaultConfig](JapaneseDefaultConfig) module:
```sh
php bin/magento setup:install --admin-firstname=Admin --admin-lastname=Admin --admin-user=admin --admin-email=admin@local.me --admin-password=123123q --base-url="http://<dev host>/" --base-url-secure="https://<dev host>" --use-secure=1 --use-secure-admin=1 --backend-frontname=admin --db-host=localhost --db-name=<db name> --db-user=<db user> --db-password=<db password> --use-rewrites=1
```    


