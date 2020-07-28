> **NOTICE**

> This project is forked from github.com/iignatov/LightOpenID. Extending for Ntpc OpenID service and add a litte auto settings. It must run with openid.php together.

# NtpcOpenID

NtpcOpenID library for easy Ntpc OpenID Authentication and Auhtorization.

- `Version....:` [**0.1**]
- `Source code:` [Official GitHub Repo :octocat:][5]
- `Author.....:` [Tiebob]

# LightOpenID

Lightweight PHP5 library for easy OpenID authentication.

- `Version....:` [**1.3.1** :arrow_double_down:][1]
  ( _see [the change log][2] for details_ )
- `Released on:` March 04, 2016
- `Source code:` [Official GitHub Repo :octocat:][3]
- `Homepage...:` http://code.google.com/p/lightopenid/
- `Author.....:` [Mewp][4]

[1]: https://github.com/iignatov/LightOpenID/archive/master.zip
[2]: https://github.com/iignatov/LightOpenID/blob/master/CHANGELOG.md
[3]: https://github.com/Mewp/lightopenid
[4]: https://github.com/Mewp
[5]: https://github.com/Tiebob/LightOpenID/archive/master.zip

## Quick start

### Download openid.php and NtpcOpenID.php from github repo above.

### Sign-on with OpenID in just 2 steps:

1. Authentication with the provider:

   ```php
   $openid = new NtpcOpenID('my-host.example.org');

   header('Location: ' . $openid->authUrl());
   ```

2. Verification:

   ```php
   $openid = new NtpcOpenID('my-host.example.org');

   if ($openid->mode) {
     echo $openid->validate() ? 'Logged in.' : 'Failed!';
   }
   ```

### Design for Ntpc OpenID:

Without setting `$openid->required` values, it has default values. If full values needed, use `$openid->setRequired(1)`. For example:

```php
$openid = new NtpcOpenID('my-host.example.org');
header('Location: ' . $openid->authUrl());
```

```php
$openid = new NtpcOpenID('my-host.example.org');
$openid->setRequired(1);
header('Location: ' . $openid->authUrl());
```

After verify correct. To get the values use:

```php
$openid->getNtpcData();
```

## Requirements

This library requires PHP >= 5.6 with cURL or HTTP/HTTPS stream wrappers enabled.

## Features

- Supports Ntpc OpenID
- Works with PHP >= 5.6

## Links

- [JavaScript OpenID Selector](http://code.google.com/p/openid-selector/) -
  simple user interface that can be used with LightOpenID.
- [HybridAuth](http://hybridauth.sourceforge.net/) -
  easy to install and use social sign on PHP library, which uses LightOpenID.
- [OpenID Dev Specifications](http://openid.net/developers/specs/) -
  documentation for the OpenID extensions and related topics.

## License

[LightOpenID](http://github.com/iignatov/LightOpenID)
is an open source software available under the
[MIT License](http://opensource.org/licenses/mit-license.php).
