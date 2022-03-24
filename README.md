
## Skill task Interview

Refactoring of ugly & weird code: calculate transactions' commissions:

https://gist.github.com/PayseraGithub/634074b26e1a2a5e4b8d39b8eb050f9f


#### How to Run:
* build:
    * git clone https://github.com/lexxusss/er_ap_ay_s.git
    * composer install
    * cp .env.example .env
    * php artisan key:generate
* Execute:
    * php artisan comissions:caclulate input.txt --dry
* Test:
    * composer test
    * composer phpcs

#### Entry point:
    CalculateCommissionsCommand
