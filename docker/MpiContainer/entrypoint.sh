#!/bin/sh
set -eu

prompt_help() {                                                                                                                                                            
cat <<-EOF >&2
Usage:
  ./entrypoint.sh -f FILENAME
EOF
}


FILENAME=""

# prepare the application environment and intermediate files
# -f <filename>
while getopts :f: op; do
  case $op in
    f)
      export FILENAME="$OPTARG"
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
if [ -z "$FILENAME" ]; then
  prompt_help
  exit 1;
else
  printf "filename is [%s]\n" "$FILENAME"
  echo "container start"
  sleep 5
  echo "container end"
fi

