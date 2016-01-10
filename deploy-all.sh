#!/bin/sh


PRACTICE_AREAS="ci-practice-areas-cpt.zip"
SLIDES="ci-slides-cpt.zip"
STAFF="ci-staff-cpt.zip"

scp $PRACTICE_AREAS 7fcnr0xvwk1nik@sftp.rax.ord.openhostingservice.com:/ci-modern-accounting-firm/htdocs/downloads/plugins/$PRACTICE_AREAS
scp $SLIDES 7fcnr0xvwk1nik@sftp.rax.ord.openhostingservice.com:/ci-modern-accounting-firm/htdocs/downloads/plugins/$SLIDES
scp $STAFF 7fcnr0xvwk1nik@sftp.rax.ord.openhostingservice.com:/ci-modern-accounting-firm/htdocs/downloads/plugins/$STAFF

PRACTICE_AREAS_METADATA="practice-areas-cpt_version_metadata.json"
SLIDES_METADATA="slides-cpt_version_metadata.json"
STAFF_METADATA="staff-cpt_version_metadata.json"

scp $PRACTICE_AREAS_METADATA 7fcnr0xvwk1nik@sftp.rax.ord.openhostingservice.com:/ci-modern-accounting-firm/htdocs/downloads/plugins/$PRACTICE_AREAS_METADATA
scp $SLIDES_METADATA 7fcnr0xvwk1nik@sftp.rax.ord.openhostingservice.com:/ci-modern-accounting-firm/htdocs/downloads/plugins/$SLIDES_METADATA
scp $STAFF_METADATA 7fcnr0xvwk1nik@sftp.rax.ord.openhostingservice.com:/ci-modern-accounting-firm/htdocs/downloads/plugins/$STAFF_METADATA
