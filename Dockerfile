FROM plesk/plesk

ADD hosted_exchange /hosted_exchange
COPY setup.sh /
