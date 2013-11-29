#!/usr/bin/env bash
git pull --rebase origin master
git pull
rm -fv cache/*
sudo /etc/init.d/nginx restart