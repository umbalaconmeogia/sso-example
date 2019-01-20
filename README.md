# sso-example
Example of implementation sso server and client

Gần đây mình có nhu cầu muốn áp dụng SSO cho nhiều hệ thống mình viết ra.
Nhưng việc tìm hiểu về SSO tương đối phức tạp, có quá nhiều giải pháp từ giao thức cho tới phạm vi áp dụng.

Cho nên quyết định tự thực hành để hiểu rõ hơn về các giải pháp này.

## Terminology

* **sso-server** An system that hold the information to authentication the user (username and password for example).
* **sso-consumer** System that users access to do their work. To use the function of sso-consumer, users must login and is redirected to sso-sever for logging in.

## References

* [Building SSO from scratch in nodejs](https://codeburst.io/building-a-simple-single-sign-on-sso-server-and-solution-from-scratch-in-node-js-ea6ee5fdf340)
* [Single sign on across multiple subdomains on yii 1](https://www.yiiframework.com/wiki/135/single-sign-on-across-multiple-subdomains)