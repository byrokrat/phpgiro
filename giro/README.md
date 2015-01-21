# ledgr/giro

Abstract package for parsing bank system files.

Usage
-----
A concrete implementation is required. See `srcOld` directory for an example.

```php
use ledgr\giro\Giro;
$giro = new Giro(new ConcreteFactory);
$file = file('file_from_bank.txt');
$domDocument = $giro->convertToXML($file);
```
