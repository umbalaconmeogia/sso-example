# SSO on subdomains on yii2

**This example will not be updated anymore. There are some bugs in the code.<br />
Now there is an extension [umbalaconmeogia/yii2-ssosubdomain](https://github.com/umbalaconmeogia/yii2-ssosubdomain) to help impelementing SSO on subdomain easily.**

## Overview

This is explanation and example code of how to config yii2 applications so that they can share single sign on in subdomains.

In this example, we have 3 sub systems:
* **login.sso-subdomain-yii2.local** *Login* sub-system that holds function to login users.
* **country.sso-subdomain-yii2.local** *Country* sub-system (it manages countries data).
* **peole.sso-subdomain-yii2.local** *People* sub-system (it manages people data).

*Country* and *People* data has no relation here. It just describes that they are different sub-systems.

Points are:
1. Config *request/cookieValidationKey* to same value on all sub-systems.
2. Config *user/identityCookie* to same value on all sub-systems.
3. Config *session/cookieParams* to same value on all sub-systems. Especially, the *domain* part of *user/identityCookie* and *session/cookieParams* should be the same.
4. Config *session/name* to different value on all sub-systems. This is the cookie key to store session id.
5. Edit actionLogin of *people* and *country* system so that they redirect to *login* system.

## Specification

* When access to any sub-system, if user is not logged in, then the user is redirected to *Login* sub-system to login.
* If user is logged in, then he can use the accessing system's function.
* These sub-systems are started in Vagrant environtments on an PC, on which the client web browser is opened for accessing to them.

## Setup

Prepare source code

### Install Vagrant

If you do not install Vagrant on your PC, then

* Install [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
* Install [Vagrant](https://www.vagrantup.com/downloads.html)

### Configure *hosts* file on client PC.

Add the following lines into the *hosts* file (on Windows, it is *C:\Windows\System32\drivers\etc\hosts*)
```
192.168.80.100      login.sso-subdomain-yii2.local
192.168.80.101      country.sso-subdomain-yii2.local
192.168.80.102      people.sso-subdomain-yii2.local
```

### Config Vargrant environtment for sub-systems.

* Create GitHub [personal API token](https://github.com/blog/1509-personal-api-tokens)
* Do steps belows on each system *login*, *country* and *people*
  * Goto *vagrant/config* folder, copy *vagrant-local.example.yml* to *vagrant-local.yml* in same folder.
  * Open *vagrant-local.yml*, paste your personal github token.
    ```
    github_token: <your-personal-github-token>
    # Read more: https://github.com/blog/1509-personal-api-tokens
    # You can generate it here: https://github.com/settings/tokens
    ```
### Vagrant up

* Goto folder of each sub-system and run
  ```
  vagrant up
  ```
  You may get error for the first time.
  Just run
  ```
  vagrant up --provision
  ```
  again and it may be OK.
  
  This will update your client PC's *hosts* file, adding sub-system's domains into *hosts*. 

### Initite user database

Login to *login* server, run yii migrate and command to create user data.
```shell
vagrant ssh
cd /app
./yii migrate
./yii user/create-user --username=admin --password=admin
```

## Test

* Access URLS
  * http://login.sso-subdomain-yii2.local
  * http://country.sso-subdomain-yii2.local
  * http://people.sso-subdomain-yii2.local
* Open
  Account: admin, Password: admin
  Account: demo, Password: demo

## References

* [How to make properly cross-subdomain authentication with Yii2 on Stackoverflow](https://stackoverflow.com/questions/34581602/how-to-make-properly-cross-subdomain-authentication-with-yii2/34704193)

* [yii2-cookbook::Managing cookies](https://github.com/samdark/yii2-cookbook/blob/master/book/cookies.md#cross-subdomain-authentication-and-identity-cookies)

## Dev memo

### Setup vagrant

Within each sub-system, first edit the original code as below:
1. Update *Vagrantfile*, change domains/app (this is the name of VM on VirtualBox). For example:
  ```
    domains = {
      app: 'country.sso-subdomain-yii2.local'
    }
  ```
2. Update *config/web.php*, edit *user* and *session*. For example:
  ```php
    'request' => [
        // cookieValidationKey should be the same in all system.
        'cookieValidationKey' => '-xgXpIztef26QPXq6MRLpsq9h2QupWpO',
    ],  
    'user' => [
        'identityClass' => 'app\models\User',
        'enableAutoLogin' => true,
        'identityCookie' => [
            'name' => '_identity',
            'httpOnly' => true,
            'domain' => '.sso-subdomain-yii2.local',
        ],
    ],
    'session' => [
        'name' => 'PHPSESSID_country', // Set name different to each sub-systems.
        'cookieParams' => [
            'domain' => '.sso-subdomain-yii2.local',
            'path' => '/',
            'httpOnly' => true,
            'secure' => false,
        ],
    ],
  ```
3. Edit *vagrant/config/vagrant-local.example.yml*, change *machine_name* and *ip*. For example:
  ```
    # Virtual machine name
    machine_name: country.sso-subdomain-yii2.local

    # Virtual machine IP
    ip: 192.168.80.101
  ```
4. Edit *vagrant/nginx/app.conf*, change *server_name* (and maybe *access_log* and *error_log* if you want to change log file names). For example:
  ```
    server_name country.sso-subdomain-yii2.local;
    root        /app/web/;
    index       index.php;

    access_log  /app/vagrant/nginx/log/country.sso-subdomain-yii2.local.access.log;
    error_log   /app/vagrant/nginx/log/country.sso-subdomain-yii2.local.error.log;  
  ```
5. Goto *vagrant/config* folder, copy *vagrant-local.example.yml* to *vagrant-local.yml* in same folder.
  Open *vagrant-local.yml*, paste your personal github token.
  ```
    github_token: <your-personal-github-token>
    # Read more: https://github.com/blog/1509-personal-api-tokens
    # You can generate it here: https://github.com/settings/tokens
  ```

### Login flow in login-system and sub-systems

* On sub-system, if user is not login, then redirect to login url (in login system), with *returnUrl* parameter.
* On login-system, remember *returnUrl* into session if specified.
* On login-system, if login successfully and *returnUrl* is specified, then redirect to *returnUrl*.