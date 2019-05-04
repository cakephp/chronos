# Basic docker based environment
# Necessary to trick dokku into building the documentation
# using dockerfile instead of herokuish
FROM php:7.3

WORKDIR /code

VOLUME ["/code"]

CMD [ '/bin/bash' ]
