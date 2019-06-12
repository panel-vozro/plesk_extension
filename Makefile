run_plesk:
	docker run --name a2com_plesk -d -p 80:80 -p 443:443 -p 8880:8880 -p 8443:8443 -p 8447:8447 a2com_plesk
bash:
	docker exec -it a2com_plesk bash
build:
	docker build -t a2com_plesk .
run_real_plesk:
	docker run --name plesk -d -p 80:80 -p 443:443 -p 8880:8880 -p 8443:8443 -p 8447:8447 plesk/plesk
reset_extension:
	docker exec a2com_plesk rm -rf /hosted_exchange
	docker cp hosted_exchange a2com_plesk:/
	docker exec a2com_plesk sh /setup.sh
