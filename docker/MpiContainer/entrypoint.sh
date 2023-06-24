#!/bin/sh
set -eu

prompt_help() {                                                                                                                                                            
cat <<-EOF >&2
Usage:
  ./entrypoint.sh -f FILENAME -d PROCESSED_NAME
EOF
}

FILENAME=""

# prepare the application environment and intermediate files
# -f <filename>
while getopts :f:d: op; do
  case $op in
    f)
      export FILENAME="$OPTARG"
      ;;
    d)
      export PROCESSED_NAME="$OPTARG"
      ;;
    \?)
      echo "Invalid option: -$OPT"
      prompt_help
      exit 1
      ;;
    :) echo "Option -$OPTARG requires an argument."
      prompt_help
      exit 1
      ;;
  esac
done

# if FILENAME is empty
if [ -z "$FILENAME" ] || [ -z "$PROCESSED_NAME" ]; then
  prompt_help
  exit 1;
else
  printf "filename is [%s], processed_name is [%s]\n" "$FILENAME" "$PROCESSED_NAME"
  echo "container start"
  sleep 10
  echo "container end"
fi

