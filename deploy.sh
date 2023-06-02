#!/bin/bash

DIR_OF_SCRIPT="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )";

if [[ $(which docker) && $(docker --version) ]]; then
    echo "Docker is avilable on the host"
  else
    echo "[ERROR] - Docker is not available on the Host. Please intall it (https://docs.docker.com/engine/install/)." 1>&2
    exit 1
fi

if [[ $(which docker-compose) && $(docker-compose --version) ]]; then
    echo "Docker-compose is avilable on the host"
  else
    echo "[ERROR] - Docker-compose is not available on the Host. Please intall it (https://docs.docker.com/compose/install/)." 1>&2
    exit 1
fi

source "${DIR_OF_SCRIPT}/environment_config.sh" || { echo "[ERROR] - Cannot source the ${DIR_OF_SCRIPT}/environment_config.sh script." 1>&2; exit 1; }

# "--remove-orphans" can be added to remove the not used containers --detach --remove-orphans
docker-compose -f "${DIR_OF_SCRIPT}/docker-compose.yml" up --build --remove-orphans || { echo "[ERROR] - Cannot run 'docker-compose up --build --no-orphan --detach' command." 1>&2; exit 1; }