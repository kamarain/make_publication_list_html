#!/bin/bash
BIB_FILES=(bibliography_A.bib
	   bibliography_B.bib)

# Merge all bib files to a single file
TEMP_BIB="TEMP_bibliography.bib"
>$TEMP_BIB
for bibf in ${BIB_FILES[@]}
do
    cat $bibf >> $TEMP_BIB
done

# Run html generation
TEMP_OUTPUT="TEMP_output.html"
php publications.php > $TEMP_OUTPUT

# Copy the full output to clipboard
xclip -selection clipboard $TEMP_OUTPUT

