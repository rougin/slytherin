# Erratum

The following are the erratum for each `README.md` found from the previous versions:

## 0.9.0

This version introduced a PSR-15 implementation based on `http-interop/http-middleware`. With this, kindly add the said package and update the packages using `composer update`:

``` diff
 {
     "require":
     {
         "filp/whoops": "~1.0",
+        "http-interop/http-middleware": "0.4.1",
         "nikic/fast-route": "~1.0",
         "rdlowrey/auryn": "~1.0",
-        "rougin/slytherin": "~0.8.0",
+        "rougin/slytherin": "~0.9.0",
         "twig/twig": "~1.0",
         "zendframework/zend-diactoros": "~1.0",
         "zendframework/zend-stratigility": "~1.0"
     }
 }
```

## 0.4.0

In this version, the `patricklouys/http` has been removed in favor for PSR-07 (`psr/http-message`). With this, kindly add a package that is compliant to PSR-07 (e.g., `zendframework/zend-diactoros`) in the `composer.json`:

``` diff
 {
     "require":
     {
         "filp/whoops": "~2.0",
         "nikic/fast-route": "~1.0",
-        "patricklouys/http": "~1.0",
         "rdlowrey/auryn": "~1.0",
-        "rougin/slytherin": "~0.3.0",
-        "twig/twig": "~1.0"
+        "rougin/slytherin": "~0.4.0",
+        "twig/twig": "~1.0",
+        "zendframework/zend-diactoros": "~1.0"
     }
 }
```

Perform `composer update` afterwards to update the specified packages.

## 0.3.0

### Usage

As per documentation, implementing interfaces are required to use Slytherin components. However in this version, the implemented third-party packages are not included (e.g., `patricklouys/http`, etc.) and needs to be installed manually. Kindly include the said packages in the `composer.json`:

``` diff
 {
     "require":
     {
+        "filp/whoops": "~1.0",
+        "nikic/fast-route": "~1.0",
+        "patricklouys/http": "~1.0",
+        "rdlowrey/auryn": "~1.0",
-        "rougin/slytherin": "~0.3.0"
+        "rougin/slytherin": "~0.3.0",
+        "twig/twig": "~1.0"
     }
 }
```