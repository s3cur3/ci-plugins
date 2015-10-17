#!/bin/sh

echo "\n\n\nDeleting all hidden files..."
find ci-slides-cpt -name '.*' -type f -delete
find ci-staff-cpt -name '.*' -type f -delete
find ci-practice-areas-cpt -name '.*' -type f -delete

echo "\n\n\nNuking existing ZIPs"
rm ci-slides-cpt.zip
rm ci-staff-cpt.zip
rm ci-practice-areas-cpt.zip

echo "\n\n\nZipping build directories"
zip -r ci-slides-cpt.zip ci-slides-cpt
zip -r ci-staff-cpt.zip ci-staff-cpt
zip -r ci-practice-areas-cpt.zip ci-practice-areas-cpt
