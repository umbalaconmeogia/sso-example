# SSO on subdomains on yii2

## Overview

This is explanation and example code of how to config yii2 applications so that they can share single sign on in subdomains.

In this example, we have 3 sub systems:
* **login.sso-subdomain-yii2.example.com** *Login* sub-system that holds function to login users.
* **country.sso-subdomain-yii2.example.com** *Country* sub-system (it manages countries data).
* **peole.sso-subdomain-yii2.example.com** *People* sub-system (it manages people data).

*Country* and *People* data has no relation here. It just describes that they are different sub-systems.

## Specification

* When access to any sub-system, if user is not logged in, then the user is redirected to *Login* sub-system to login.
* If user is logged in, then he can use the accessing system's function.
* This sub-systems will be started in Vagrant environtments.

## Setup

### Config Vargrant environtment for sus-systems.

### Configure *hosts* file on client PC.

## Test


## References

* [How to make properly cross-subdomain authentication with Yii2 on Stackoverflow](https://stackoverflow.com/questions/34581602/how-to-make-properly-cross-subdomain-authentication-with-yii2/34704193)