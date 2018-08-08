#!/bin/bash
docker run \
    --rm \
    --net=magento \
    --ip=172.20.0.101 \
    -v ~/.composer/auth.json:/root/.composer/auth.json \
    -v `pwd`:/module_source \
    yireo/magento2devbox /module_source/docker/run.sh

