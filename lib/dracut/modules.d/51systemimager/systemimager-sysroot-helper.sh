#!/bin/bash
#
#    vi:set filetype=bash et ts=4:
#
#    This file is part of SystemImager.
#
#    SystemImager is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 2 of the License, or
#    (at your option) any later version.
#
#    SystemImager is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with SystemImager. If not, see <https://www.gnu.org/licenses/>.
#
#    Copyright (C) 2017-2019 Olivier LAHAYE <olivier.lahaye1@free.fr>
#
#    Purpose:
#      This file is run by /init (old dracut)
#      It'll help init to find root filesystem to mount

type shellout >/dev/null 2>&1 || . /lib/systemimager-lib.sh

logstep "systemimager-sysroot-helper"

test -r /tmp/root.info && . /tmp/root.info
test -z "$NEWROOT" && NEWROOT=/sysroot
unset netroot

logdebug "Root filesysterm informations:"
logdebug "NEWROOT=$NEWROOT"
logdebug "root=$root"
logdebug "rflags=$rflags"
logdebug "fstype=$fstype"
