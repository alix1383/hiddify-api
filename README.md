# Hiddify-API

![GitHub repo size](https://img.shields.io/github/repo-size/alix1383/hiddify-api?style=for-the-badge)

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
    - [x] PHP 🐘 [Code](https://github.com/alix1383/hiddify-api/blob/main/api.php) | [Doc](https://github.com/alix1383/hiddify-api#-usage-)
    - [ ] Python 🐍 *need help 
    - [ ] NodeJS ✨ *need help
    - MORE...

  - ### MISC  
    - [ ] Write Doc
    - [ ] Error Handling

<br>

## 💡 Usage :

``` php
<?php

include('api.php');

$api = new hiddifyApi(
    '', //! https://domain.com
    '', //! hiddify hidden path
    '' //! admin secret
);

$api->is_connected(); // return bool

$api->getSystemStats(); // return array


/////----------- USER API -----------\\\\\

$api->user->addUser(string $name,
                    int $package_days = 30,
                    int $package_size = 30,
                    ?string $telegram_id = null,
                    ?string $comment = null
                    string $resetMod = 'no_reset' //# 'no_reset' | 'monthly' | 'weekly' | 'daily' ); //return bool

$api->user->getUserList(); // return array

$api->user->getUserdetais(string $uuid); // return array

?>
```

## 🤝 Contributing :
Contributions to this project are always welcome! Feel free to submit a pull request or create an issue if you encounter any problems.

## 📃 License :
This project is licensed under the MIT License. See the [LICENSE](https://github.com/alix1383/hiddify-api/blob/main/LICENSE) file for more information.
