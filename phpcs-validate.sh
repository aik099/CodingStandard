#!/bin/env sh
if [ $# -lt 1 ]; then
	echo -e "Usage: $0 <sniff.name> <report-name>"
	exit 1;
fi

CS="CodingStandard"
SNIFF=${1#$CS.}
TEST_DATA_FILE="$CS/Tests/${SNIFF/.//}UnitTest.*inc"
REPORT="${2-full}"
PATCH_FILE="tmp.patch"

if [ $REPORT == "diff" ]; then
	echo "1. creating patch '${PATCH_FILE}' ..."
	vendor/bin/phpcs --report=$REPORT --standard=$CS --sniffs=$CS.$SNIFF $TEST_DATA_FILE > $PATCH_FILE
	
	echo "2. applying patch '${PATCH_FILE}' ..."
	patch -p0 -ui $PATCH_FILE

	FILES=`ls $TEST_DATA_FILE`

	echo "3. creating fixed files for each fixture file"
	for FILE in $FILES; do
		cp -f $FILE $FILE.fixed
	done

	patch -p0 -R -ui $PATCH_FILE
else
	echo -e "Report: $REPORT"
	vendor/bin/phpcs -vs --report=$REPORT --report-width=120 --standard=$CS --sniffs=$CS.$SNIFF $TEST_DATA_FILE
fi

