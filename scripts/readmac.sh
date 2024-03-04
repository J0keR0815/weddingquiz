#!/bin/bash

if [ $# -ne 1 ]
then
	echo "Usage Error: $0 <ip>"
	exit 1
fi

echo "$1" | grep -qoE \
	'(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.
	(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.
	(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.
	(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)'

if [ $? -ne 0 ]
then
	echo "Usage Error: $0 <ip>"
	exit 2
fi

ip=$1

arp -d $ip > /dev/null 2>&1
ping -c 1 $ip > /dev/null 2>&1
arp -a | grep $ip | cut -d " " -f 4
