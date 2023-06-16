<h1>Hiddify-API</h1>

![GitHub repo size](https://img.shields.io/github/repo-size/alix1383/hiddify-api?style=for-the-badge)

#### This is a third-party library for [Hiddify](https://github.com/hiddify)

<br>


## 📑 TODO :

- support more language 
  - [ ] python 🐍


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
                    ?string $comment = null); // return bool

$api->user->getUserList(); // return array

$api->user->getUserdetais(string $uuid); // return array

?>
```

## 🤝 Contributing :
Contributions to this project are always welcome! Feel free to submit a pull request or create an issue if you encounter any problems.

## 📃 License :
This project is licensed under the MIT License. See the [LICENSE](https://github.com/alix1383/hiddify-api/blob/main/LICENSE) file for more information.
