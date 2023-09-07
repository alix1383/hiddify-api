# Hiddify-API

### This is a third-party library For [Hiddify](https://github.com/hiddify)

<br>

## 📑 TODO :

- ### API

  - #### Misc
    - [x] Is Conected
    - [x] Get System Stats
  - #### User
    - [x] Get user list
    - [x] Get User Info + Servers & Time Remain
    - [x] Add User
    - [ ] Del User
    - [ ] Del deactive Usres
    - [ ] Get Telegram Proxy If available
  - #### Admin
    - [ ] Get Admin list
    - [ ] Add New Admin
    - [ ] Del admin

- ### Support More Language

  - [x] PHP 🐘 [Code](https://github.com/alix1383/hiddify-api/blob/main/php/api.php) | [Doc](https://github.com/alix1383/hiddify-api#-usage-)
  - [x] NodeJS ✨ [Code](https://github.com/alix1383/hiddify-api/blob/main/api.php) | [Doc](https://github.com/alix1383/hiddify-api#-usage-nodejs-)
  - [ ] Python 🐍 \*need help
  - MORE...

- ### MISC
  - [ ] Write Doc
  - [ ] Error Handling

<br>

## 💡 Usage Php :

```php
<?php

include('php/api.php');

$api = new hiddifyApi(
    '', //! https://domain.com
    '', //! hiddify hidden path
    '' //! admin secret
);

$api->is_connected(); // return bool

$api->getSystemStats(); // return array


/////----------- USER API -----------\\\\\

$api->User->addUser(string $name,
                    int $package_days = 30,
                    int $package_size = 30,
                    ?string $telegram_id = null,
                    ?string $comment = null
                    string $resetMod = 'no_reset'); //! if success return user uuid else return false

$api->User->getUserList(); // return array

$api->User->getUserdetais(string $uuid); // return array

?>
```

<br>

## 💡 Usage Node js :

Be sure to install axios,moment,dotenv modules before running !

```js
import hiddifyApi from "node-js/api.js";

const api = new hiddifyApi(); // first edit your .env file

api.is_connected(); // return bool

api.getSystemStatus(); // return array

/////----------- USER API -----------\\\\\
const uuid = api.generateuuid();
api.addServise({ uuid, comment: "test", name: "hiddify api", day: 30, traficc: 25, telegram_id: 123456 }); //! if success return user uuid else return false

api.getUserList(); // return array

api.findServise(uuid); // return array  for find information uuid
```

## 🤝 Contributing :

Contributions to this project are always welcome! Feel free to submit a pull request or create an issue if you encounter any problems.

## 📃 License :

This project is licensed under the MIT License. See the [LICENSE](https://github.com/alix1383/hiddify-api/blob/main/LICENSE) file for more information.
