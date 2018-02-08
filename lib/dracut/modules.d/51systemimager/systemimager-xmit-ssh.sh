#!/bin/sh
#
# "SystemImager" 
#
#  Copyright (C) 1999-2017 Brian Elliott Finley <brian@thefinleys.com>
#
#  $Id$
#  vi: set filetype=sh et ts=4:
#
#  Code mainly written by Olivier LAHAYE.
#
# This file hosts functions related to ssh deployment

################################################################################
#
# init_transfer
# - download transfer config (example: download the .torrent files)
# - detect or get staging dir
# - create the staging dir if needed
# - get image size to download
# - check it fits in destination (staging dir or system)
#
################################################################################
#
function init_transfer() {
}

################################################################################
#
# get_image_size <image_name>
#    => Returns the image size to be downloaded in bytes.
#
################################################################################
function get_image_size() {

	echo 123456789
}

################################################################################
# download_image
#    => Download the image ${IMAGENAME}
#    => example: download_image
#
################################################################################
#
function download_image() {
	loginfo "Downloading image"
}

#################################################################################
# extract_image
#    => extract image in destination
#    => example: extract_image
#
################################################################################
#
function extract_image() {
	loginfo "Extracting image to /sysroot"
	send_monitor_msg "status=107:speed=0" # 107=extracting
}

#################################################################################
# install_overrides()
#    => extract overrides in /sysroot
#
################################################################################
#
function install_overrides() {
	loginfo "Installing overrides"
}

################################################################################
# terminate_transfer()
#    => stops any remaining processes related to transfert
#       (ssh tunnels, torrent seeding processes, ...)
#
################################################################################
function terminate_transfer() {
	loginfo "Terminating transfer processes."
	# Explicitly kill sleep processes.
	# This is needed to close the SSH tunnel on the image server when the
	# SSH transport is used).
	killall sleep >/dev/null 2>&1
}

################################################################################
#
# INTERNAL functions below. will be prefixed with _
#
################################################################################

