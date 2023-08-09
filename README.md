## How to run

Please add this kind of file first in the root project:
```
PAGE_SIZE=10
LTP_VALIDITY_DAYS=1

DB_HOST=database
DB_USER=paroparog
DB_NAME=paroparogdb
DB_PASS=paroparog

EMAIL_HOST=mailhog
EMAIL_PORT=1025
EMAIL_FROM=system@gmail.com
EMAIL_FROM_NAME=Paro Paro G
EMAIL_AUTH=false
```

Then run it:
```
docker-compose up
```
