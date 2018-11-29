# php-stitch-client
A very simplistic Stitch Import API client library for php.


### Some important notes
When pushing data at the API there is two fairly undocumented features
that should be noted.

#### date and datetime
First you should note that the API doesn't support datetime. At first
I thought this was a bad thing, but after som soulsearching I have
concluded that this actually isnt't breaking anything. Just use the
date format `YYYYMMDD` and cast it to an int before pushing it into the
Stich Client liberary. Dates will be stored as integers and is easy to
both join and do filtering on. I guess most datatargets have great support
for handling numbers.


#### Floats and numbers
The stitch API distincts between numbers and floats. When sending stuff
with JSON, PHP for instance will by default remove zeros from floats.
To prevent this behaviour this small liberary uses the `JSON_PRESERVE_ZERO_FRACTION`
flag.

```php
json_encode($body , JSON_PRESERVE_ZERO_FRACTION);
```

Might be helpful for other implementations.