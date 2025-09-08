#!/bin/sh
# wait-for-it.sh

# Usage: wait-for-it.sh host:port [-t timeout] [-- command args]
# Waits for a host:port to be available, then executes a command.

TIMEOUT=15
QUIET=0

echoerr() {
  if [ "$QUIET" -ne 1 ]; then echo "$@" 1>&2; fi
}

usage() {
  cat << USAGE >&2
Usage:
  $(basename "$0") host:port [-t timeout] [-- command args]
  -h | --help          Show this message
  -q | --quiet         Do not output any message
  -t | --timeout=TIMEOUT Timeout in seconds, zero for no timeout
  -- COMMAND ARGS       Execute command with args after the host is available
USAGE
  exit 1
}

wait_for() {
  local start_ts=$(date +%s)
  echoerr "Waiting for $HOST:$PORT..."
  while :
  do
    if [ "$ISBUSY" = "true" ]; then
      nc -z "$HOST" "$PORT" > /dev/null 2>&1
      result=$?
    else
      (echo > /dev/tcp/"$HOST"/"$PORT") > /dev/null 2>&1
      result=$?
    fi

    if [ $TIMEOUT -gt 0 ] && [ $(( $(date +%s) - start_ts )) -ge $TIMEOUT ]; then
      echoerr "Timeout occurred after $TIMEOUT seconds waiting for $HOST:$PORT."
      exit 1
    fi

    sleep 1
  done
  return 0
}

parse_arguments() {
  while [ "$#" -gt 0 ]; do
    case "$1" in
      *:* )
        HOST=$(printf "%s\n" "$1"| cut -d : -f 1)
        PORT=$(printf "%s\n" "$1"| cut -d : -f 2)
        shift 1
        ;;
      -q | --quiet )
        QUIET=1
        shift 1
        ;;
      -t | --timeout )
        TIMEOUT="$2"
        if [ "$TIMEOUT" = "" ]; then
          echoerr "Error: timeout value missing"
          usage
        fi
        shift 2
        ;;
      -- )
        shift
        CLI=("$@")
        return 0
        ;;
      * )
        echoerr "Unknown argument: $1"
        usage
        ;;
    esac
  done
  return 0
}

parse_arguments "$@"

if [ -z "$HOST" ] || [ -z "$PORT" ]; then
  echoerr "Error: host and/or port not specified."
  usage
fi

wait_for

if [ "${#CLI[@]}" -gt 0 ]; then
  exec "${CLI[@]}"
fi