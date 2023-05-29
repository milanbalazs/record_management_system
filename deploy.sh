#!/bin/bash

DIR_OF_SCRIPT="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )";

source "${DIR_OF_SCRIPT}/environment_config.sh" || { echo "Cannot source the ${DIR_OF_SCRIPT}/environment_config.sh script." 1>&2; exit 1; }

# "--remove-orphans" can be added to remove the not used containers --detach --remove-orphans
docker-compose -f "${DIR_OF_SCRIPT}/docker-compose.yml" up --build --remove-orphans || { echo "Cannot run 'docker-compose up --build --no-orphan --detach' command." 1>&2; exit 1; }