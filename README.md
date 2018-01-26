# VaderLabSecurityBundle

Bundle for the Symfony framework
Authorization and work with the Vaderlab API.


## Getting Started

### Requirements

```
    "php": ">=7.2",
    "symfony/symfony": ">=3.4",
    "guzzlehttp/guzzle": "~6.0"
```

### Installing

Update composer.json

```
"require" : {
    [...]
    "vaderlab/security-bundle" : "^v1.0"
},
    "repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/vaderlab-com/SecurityBundle.git"
    }],
```


And install bundle 

```
composer update vaderlab/security-bundle
```


Example configuration for app firewall in security.yml
```
security:
    acl:
        connection: default

    providers:
        vaderlab:
            id: 'vaderlab.security.user_provider'

    firewalls:
        api:
            pattern: ^/api
            stateless: true
            anonymous: false
            simple_preauth:
                authenticator: 'vaderlab.security.authentificator'
            provider: 'vaderlab'

```

Configure config.yml

```
vaderlab:
    security:
        api:
            cache_provider: ~                       # CacheProvider service. Must be implemented VaderLab/SecurityBundle/Service/Cache/CacheProviderInterface.php
            api_key: '<your api key>'               # User api key
            timeout: 2                              # Timeout for requests to the API
            url: https://www.vaderlab.com/api       # Url to the VaderLab API backend
```

## Running the tests

@todo

## Authors

* **Stanislau Komar** - *Initial work* - [Asisyas](https://github.com/Asisyas)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

