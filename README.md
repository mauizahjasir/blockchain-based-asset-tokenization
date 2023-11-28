# Asset Tokenization

Unlock new possibilities with blockchain asset tokenization – where tangible assets become digital tokens, fostering accessibility, liquidity, and a revolutionary era of ownership

## Installation

Clone the repository from github url [https://github.com/mauizahjasir/ms-thesis](https://github.com/mauizahjasir/ms-thesis) or run the git clone command on git bash:

```bash
git clone git@github.com:mauizahjasir/ms-thesis.git
```
### Multichain Setup

* Navigate to [https://www.multichain.com/download-install/](https://www.multichain.com/download-install/)
* Under Installing MultiChain on Windows, download the ZIP file and extract its contents into a folder
* To create and start blockchain follow this tutorial [https://dzone.com/articles/how-to-get-started-with-multichain-blockchain-on-w](https://dzone.com/articles/how-to-get-started-with-multichain-blockchain-on-w)

### Application Setup

* Start xampp (Apache and Mysql)
* rename the file `.env.example` to `.env`
* Configure blockchain parameters (Port, Password, chain name)
* Install composer and node modules (Make sure you have both composer and npm installed and configured in your system environment)
```bash
> composer install

> npm run dev
```
* Setup new database in mysql with the same name provided in the .env file against `DB_DATABASE`
* Run following commands in the same order, but before make sure your multichain port is up and running using -daemon command
```
> php artisan key:generate
> php artisan optimize:clear
> php artisan migrate
> php artisan db:seed RoleSeeder
> php artisan db:seed UsersSeeder
```

### Running Application
To start application on browser run both commands in separate terminal:
```
> php artisan serve

> npm run dev
```
