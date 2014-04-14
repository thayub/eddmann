#!/usr/bin/env bash
sudo git checkout cache/.gitignore
git pull --rebase origin master
git pull
sudo rm -fv cache/*
sudo /etc/init.d/nginx restart