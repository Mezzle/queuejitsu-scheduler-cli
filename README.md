# QueueJitsu Scheduler CLI

[![Build Status](https://travis-ci.org/Mezzle/queuejitsu-scheduler-cli.svg?branch=master)](https://travis-ci.org/Mezzle/queuejitsu-scheduler-cli)

CLI for QueueJitsu Scheduler

If you want to use QueueJitsu, and Scheduler, just pull this

## Usage

```                                                                                                                   dev/queuejitsu-scheduler-cli (master ⚡) J00349-notebook-MartinM
Usage:
  vendor/bin/qjitsu scheduler:run [options]

Options:
  -b, --background         Run in the background
  -i, --interval=INTERVAL  How long to wait before checking for new jobs when none are available (in seconds) [default: 5]
      --pidfile=PIDFILE    Location of Pidfile
  -h, --help               Display this help message
  -q, --quiet              Do not output any message
  -V, --version            Display this application version
      --ansi               Force ANSI output
      --no-ansi            Disable ANSI output
  -n, --no-interaction     Do not ask any interactive question
  -v|vv|vvv, --verbose     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
